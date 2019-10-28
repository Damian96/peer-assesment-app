<?php


namespace App\Notifications;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;

class AppResetPasswordEmail extends Mailable implements Renderable
{
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    protected $notifiable;
    protected $code;

    /**
     * AppVerifyEmail constructor.
     * @param MustVerifyEmail $notifiable
     * @param string $code
     * @param \Closure|null $toMailCallback
     */
    public function __construct(MustVerifyEmail $notifiable, string $code, \Closure $toMailCallback = null)
    {
        $this->notifiable = $notifiable;
        $this->code = $code;
        self::$toMailCallback = $toMailCallback;
    }

    /**
     * Build the message.
     * @return MailMessage|mixed
     */
    public function build() {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $this->notifiable, $this->code);
        }
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(config('auth.password_reset.strings.subject'))
            ->markdown('emails.reset')
            ->with('code', $this->code);
    }
}
