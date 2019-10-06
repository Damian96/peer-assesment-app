<?php


namespace App\Notifications;


use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Config;

class AppVerifyEmail extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
            ->subject(Lang::get(Config::get('auth.verification.strings.subject')))
            ->line(Lang::get(Config::get('auth.verification.strings.heading')))
            ->action(Lang::get(Config::get('auth.verification.strings.action')), $verificationUrl)
            ->line(Lang::get(Config::get('auth.verification.strings.notice')));
    }
}
