<?php


namespace App\Notifications;


use App\Course;
use App\Session;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * @property  \App\Session session
 * @property User instructor
 */
class SessionStartEmail extends Mailable implements Renderable
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
    protected $markdown = 'emails.session';

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
     * @param \App\User $instructor
     * @param Course $course
     * @param Session $session
     * @param \Closure $toMailCallback The callback to executed when the mail is sent.
     */
    public function __construct(MustVerifyEmail $notifiable, \App\User $instructor, Course $course, Session $session, \Closure $toMailCallback = null)
    {
        $this->notifiable = $notifiable;
        $this->course = $course;
        $this->session = $session;
        $this->instructor = $instructor;
        self::$toMailCallback = $toMailCallback;
    }

    /**
     * Build the message.
     * @return MailMessage|mixed
     */
    public function build()
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $this->notifiable);
        }
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(sprintf("Course %s has a new Session!", $this->course->code))
            ->markdown($this->markdown) // emails.session
            ->with([
                'user' => $this->notifiable,
                'instructor' => $this->instructor,
                'course' => $this->course,
                'session' => $this->session
            ]);
    }
}
