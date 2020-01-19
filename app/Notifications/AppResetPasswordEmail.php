<?php


namespace App\Notifications;


use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class AppResetPasswordEmail extends Mailable implements Renderable
{
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    protected $markdown = 'emails.reset';

    protected $resettable;
    protected $token;

    /**
     * AppVerifyEmail constructor.
     * @param Model|CanResetPassword $resettable
     * @param \Closure|null $toMailCallback
     */
    public function __construct(CanResetPassword $resettable, \Closure $toMailCallback = null)
    {
        $this->resettable = $resettable;
        self::$toMailCallback = $toMailCallback;
    }

    /**
     * @param Model $resettable
     * @return string
     */
    protected function resettingUrl(Model $resettable)
    {
        return URL::temporarySignedRoute(
            'user.verify',
            Carbon::now()->addMinutes(config('auth.password_reset.expire', 60)),
            [
                'id' => $resettable->getKey(),
                'hash' => sha1($resettable->getEmailForVerification()),
                'action' => 'password',
                'token' => $resettable->password_reset_token
            ]
        );
    }

    /**
     * Build the message.
     * @return MailMessage|mixed
     */
    public function build()
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $this->resettable);
        }
        $resettingUrl = $this->resettingUrl($this->resettable);
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(config('auth.password_reset.strings.subject'))
            ->markdown($this->markdown)
            ->with(['url' => $resettingUrl]);
    }
}
