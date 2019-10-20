<?php


namespace App\Notifications;


use App\Models\Course;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class StudentInviteEmail extends Mailable implements Renderable
{
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    protected $notifiable;

    /**
     * The Markdown template for the message (if applicable).
     *
     * @var string
     */
    protected $markdown = 'emails.invite';

    /**
     * The Course that the Student was invited to.
     *
     * @var Course
     */
    protected $course;

    /**
     * StudentInviteEmail constructor.
     * @param MustVerifyEmail $notifiable
     * @param Course $course
     * @param Closure|null $toMailCallback The callback to executed when the mail is sent.
     */
    public function __construct(MustVerifyEmail $notifiable, Course $course, Closure $toMailCallback = null)
    {
        $this->notifiable = $notifiable;
        $this->course = $course;
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
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
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
        $parameters = [
            'url' => $verificationUrl,
            'heading' => 'You are invited to ' . config('app.name'),
            'description' => 'You are invited to ' . $this->course->title,
            'action' => 'Enroll to ' . $this->course->code,
            'notice' => 'If you are already enrolled in the course, you can skip this email.',
            'course' => $this->course,
            'user' => $this->notifiable
        ];
        return $this->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
            ->subject($parameters['heading'])
            ->markdown($this->markdown)
            ->with($parameters);
    }

}
