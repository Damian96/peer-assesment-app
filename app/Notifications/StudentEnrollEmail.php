<?php


namespace App\Notifications;


use App\Course;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class StudentEnrollEmail extends Mailable implements Renderable
{
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * @var Model|MustVerifyEmail
     */
    protected $notifiable;

    /**
     * The Markdown template for the message (if applicable).
     *
     * @var string
     */
    protected $markdown = 'emails.enroll';

    /**
     * The Course that the Student was invited to.
     *
     * @var \App\Course
     */
    protected $course;

    /**
     * The verification action.
     * @var string
     */
    private $action = 'enroll';

    /**
     * StudentInviteEmail constructor.
     * @param MustVerifyEmail $notifiable
     * @param Course $course
     * @param \Closure $toMailCallback The callback to executed when the mail is sent.
     */
    public function __construct(MustVerifyEmail $notifiable, Course $course, \Closure $toMailCallback = null)
    {
        $this->notifiable = $notifiable;
        $this->course = $course;
        self::$toMailCallback = $toMailCallback;
    }

    /**
     * Build the message.
     * @return MailMessage|mixed
     */
    public function build() {
        $verificationUrl = $this->notifiable->verificationUrl($this->action);
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $this->notifiable, $verificationUrl);
        }
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject("You have been enrolled to " . $this->course->code)
            ->markdown($this->markdown)
            ->with([
                'url' => $verificationUrl,
                'user' => $this->notifiable,
                'course' => $this->course
            ]);
    }
}
