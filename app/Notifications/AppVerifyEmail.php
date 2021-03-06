<?php


namespace App\Notifications;


use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class AppVerifyEmail extends Mailable implements Renderable
{
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    protected $notifiable;

    /**
     * AppVerifyEmail constructor.
     * @param MustVerifyEmail $notifiable
     * @param Closure|null $toMailCallback
     */
    public function __construct(MustVerifyEmail $notifiable, Closure $toMailCallback = null)
    {
        $this->notifiable = $notifiable;
        self::$toMailCallback = $toMailCallback;
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  Model|MustVerifyEmail  $notifiable
     * @return string
     */
    protected function verificationUrl(Model $notifiable)
    {
        return URL::temporarySignedRoute(
            'user.verify',
            Carbon::now(config('app.timezone'))->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
                'action' => 'email'
            ]
        );
    }

    /**
     * Build the message.
     * @return MailMessage|mixed
     */
    public function build() {
        $verificationUrl = $this->verificationUrl($this->notifiable);
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $this->notifiable, $verificationUrl);
        }
        $this->notifiable->getEmailForVerification();
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(config('auth.verification.strings.subject'))
            ->markdown('emails.verify')
            ->with([ 'url' => $verificationUrl ]);
    }
}
