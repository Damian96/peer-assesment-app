<?php

namespace App;

use App\Notifications\AppResetPasswordEmail;
use App\Notifications\StudentEnrollEmail;
use App\Notifications\StudentInviteEmail;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Auth\Passwords\CanResetPassword as Resettable;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class User
 *
 * @package App
 * @property int $id
 * @property string $fname
 * @property string $lname
 * @property string $email
 * @property string $password
 * @property int $admin
 * @property int $instructor
 * @property int|null $email_verified_at
 * @property int $created_at
 * @property int|null $updated_at
 * @property string|null $remember_token
 * @property string|null $verification_token
 * @property string|null $department
 * @property string|null $password_plain
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder where(string $string, String $email)
 * @method static User|Model firstOrFail(string $string, String $email)
 * @method static User|Model findOrFail(string $string, String $email)
 * @mixin \Eloquent
 * @property string|null $reg_num
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Course[] $studentCourses
 * @property-read int|null $student_courses_count
 * @property string api_token
 * @property string last_login
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereInstructor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRegNum($value)
 * @method static whereApiToken(string $token)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\StudentCourse[] $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Session[] $sessions
 * @property-read int|null $sessions_count
 * @property string password_reset_token
 * @property Group group
 * @property string fullname
 * @property InstructorConfig config
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastLogin($value)
 * @noinspection PhpFullyQualifiedNameUsageInspection
 */
class User extends Model implements Authenticatable, MustVerifyEmail, CanResetPassword, Authorizable
{
    use Notifiable;
    use Resettable;
    use SendsPasswordResetEmails;

    const RAW_FULL_NAME = 'CONCAT(SUBSTR(fname, 1, 1), ". ", lname) AS full_name';
    const PA_WEIGHT_GROUP = .5;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $connection = 'mysql';

    /**
     * The student's newly generated password.
     * @see \App\Notifications\StudentInviteEmail
     * @var string|null
     */
    public $password_plain = null;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'admin'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'email_verified_at' => null,
        'updated_at' => null,
        'remember_token' => null,
        'instructor' => '0',
        'admin' => '0',
        'department' => null,
        'reg_num' => null,
        'fname' => null,
        'lname' => null
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'fname', 'lname', 'reg_num', 'department', 'instructor', 'last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_token', 'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'last_login' => 'datetime',
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'instructor' => 'int',
        'admin' => 'int',
    ];

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            /**
             * @var \App\User $model
             */
            if ($model->hasVerifiedEmail())
                $model->generateApiToken();

            if ($model->group())
                $model->group = $model->group();
        });

        self::retrieved(function ($model) {
            /**
             * @var \App\User $model
             */
            $config = $model->config();
            if ($config instanceof InstructorConfig)
                $model->config = $config;
        });
    }

    /**
     * @see https://www.php.net/manual/en/language.oop5.overloading.php#object.set
     * @param string $key
     * @param mixed $value
     * @throws \Exception
     */
    public function __set($key, $value)
    {
        switch ($key) {
            case 'token':
            case 'password_reset_token':
                $this->password_reset_token = $value;
                break;
            default:
                parent::__set($key, $value);
        }
    }

    /**
     * @see https://www.php.net/manual/en/language.oop5.overloading.php#object.get
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        switch ($key) {
            case 'token':
            case 'password_reset_token':
                return $this->getPasswordResetToken();
            case 'registration_date': # Alias of 'registered_at'
                try {
                    $mutable = new Carbon($this->created_at, config('app.timezone'));
                    return $mutable->format(Config::get('constants.date.full'));
                } catch (\Exception $e) {
                    return $this->created_at;
                }
            case 'last_login_full':
                try {
                    $mutable = new Carbon($this->last_login, config('app.timezone'));
                    return $mutable->format(Config::get('constants.date.full'));
                } catch (\Exception $e) {
                    return $this->last_login;
                }
            case 'updated_date': # Alias of 'update_at'
                try {
                    $mutable = new Carbon($this->updated_at, config('app.timezone'));
                    return $mutable->format(Config::get('constants.date.full'));
                } catch (\Exception $e) {
                    return $this->updated_at;
                }
            case 'verification_date': # Alias of 'verified_at'
                if (empty($this->email_verified_at)) {
                    return 'No';
                } else {
                    try {
                        $mutable = new Carbon($this->email_verified_at, config('app.timezone'));
                        return $mutable->format(Config::get('constants.date.full'));
                    } catch (\Exception $e) {
                        return $this->email_verified_at;
                    }
                }
            case 'department_title':
                return self::getDepartmentTitle($this->department);
            case 'name':
            case 'fullname':
            case 'full_name':
                return substr($this->fname, 0, 1) . '. ' . $this->lname;
            case 'role':
                if ($this->admin == 1) return 'Admin';
                elseif ($this->isInstructor()) return 'Instructor';
                else return 'Student';
            case 'crumbs':
                return empty($this->crumbs) ? [] : $this->crumbs;
            case 'last_login':
            default:
                return parent::__get($key);
        }
    }

    /**
     * Retrieve the department's title according to the specified abbreviation
     * @param string $abbr
     * @return string
     */
    public static function getDepartmentTitle(string $abbr)
    {
        switch ($abbr) {
            case 'CS':
            case 'CCP':
                return 'Computer Science';
            case 'ES':
            case 'CES':
                return 'English Studies';
            case 'PSY':
            case 'CPY':
                return 'Psychology Studies';
            case 'BS':
            case 'CBE':
                return 'Business Administration & Economics';
            case 'MBA':
                return 'Executive MBA';
            default:
                return 'N/A';
        }
    }

    /**
     * Retrieve the department's short code according to the specified abbreviation
     * @param string $abbr
     * @return string The short department code
     */
    public static function getDepartmentCode(string $abbr)
    {
        switch ($abbr) {
            case 'CS':
                return 'CCP';
            case 'ES':
                return 'CES';
            case 'PSY':
                return 'CPY';
            case 'BS':
                return 'CBE';
            case 'MBA':
                return 'MBA';
            default:
                return 'N/A';
        }
    }

    /**
     * Get the StudentGroup record associated with the student.
     * @return bool|Model|\Illuminate\Database\Query\Builder|object
     */
    public function group()
    {
        $ss = $this->hasOne(\App\StudentGroup::class, 'user_id', 'id');
        return $ss->exists() ? $ss->first()->group()->first() : false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(\App\StudentGroup::class, 'user_id', 'id');
    }

    /**
     * Get the InstructorConfig record associated with the instructor.
     * @return bool|\App\User
     */
    public function config()
    {
        if (!$this->isInstructor()) return false;
        $ss = $this->hasOne(\App\InstructorConfig::class, 'user_id', 'id');
        return $ss->exists() ? $ss->first()->getModel() : false;
    }

    /**
     * Get the Student's \App\StudentSession related record.
     */
    public function session()
    {
        return $this->hasOne(\App\StudentSession::class, 'user_id', 'id');
    }

    /**
     * Get the student's teammates from the database
     * @param Session $session
     * @param bool $self
     * @return \Illuminate\Support\Collection
     */
    public function teammates(Session $session, bool $self = false)
    {
        if (!Auth::user()->isStudent()) return new Collection();
        $groups = array_column($this->groups()->get(['group_id'])->toArray(), 'group_id');

        $group_id = DB::table('groups')
            ->where('session_id', '=', $session->id)
            ->whereIn('id', $groups)
            ->get(['groups.id'])->first()->id;

//        if (empty($group_id))
//            return new Collection();

        $q = DB::table($this->table)
            ->join('user_group', 'user_group.user_id', '=', 'users.id')
            ->whereNotNull('email_verified_at')
            ->where('user_group.group_id', '=', $group_id)
            ->selectRaw(self::RAW_FULL_NAME);

        if ($self)
            $q = $q->where('users.id', '!=', $this->id);

        return $q->addSelect('users.*')->get(['users.*']);
    }

    /**
     * Get the course record associated with the instructor.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Relations\HasManyThrough|false The relation or false on failure
     */
    public function courses()
    {
        if ($this->isAdmin() || $this->isInstructor()) {
            return $this->hasMany('\App\Course');
        } else if ($this->isStudent()) {
            return $this->hasMany('\App\StudentCourse');
        }
        return false;
    }

    /**
     * Get the Session records associated with the instructor / admin.
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function sessions()
    {
        return $this->hasManyThrough('\App\Session', '\App\Course', 'user_id', 'course_id', 'id', 'id');
    }

    /**
     * Get the Form records associated with the instructor / admin.
     * @return Form[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function forms()
    {
        return Form::query()
            ->whereIn('session_id', array_column($this->sessions()->toBase()->select('sessions.id')->get(['sessions.id'])->toArray(), 'id'))
            ->get();
    }

    /**
     * Check if the instructor owns the specified course
     * @param int $id
     * @return bool
     */
    public function ownsCourse(int $id)
    {
        return $this->courses()->get()->toBase()->contains('id', ' = ', $id);
    }

    /**
     * Check if the student is registered in the specified course
     * @param int $course_id
     * @return bool
     */
    public function isRegistered(int $course_id)
    {
        return StudentCourse::whereUserId($this->id)
            ->where('course_id', '=', $course_id)
            ->exists();
    }

    /**
     * Retrieve the courses that this student is registered on
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentCourses()
    {
        return $this->hasMany(\App\StudentCourse::class, 'user_id', 'id');
    }

    /**
     * Retrieve the Sessions that this student has submitted
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentSessions()
    {
        return $this->hasMany(\App\StudentSession::class, 'user_id', 'id');
    }

    /**
     * Retrieve a user by his email
     *
     * @param $email String The user's email.
     * @return User|Model
     */
    public static function getUserByEmail($email)
    {
        return self::where('email', $email)->first()->refresh();
    }

    /**
     * Retrieve all verified students
     * @return \Illuminate\Support\Collection
     */
    public static function getAllStudents()
    {
        return self::whereInstructor('0')
            ->where('admin', '=', '0')
            ->where('id', '!=', Course::DUMMY_ID)
            ->whereNotNull('email_verified_at')
            ->get();
    }

    /**
     * Get all the instructors
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|Model[]|\Illuminate\Database\Query\Builder[]
     */
    public static function getInstructors()
    {
        return self::whereInstructor('1')
            ->where('admin', '=', '0')
            ->whereNotNull('email_verified_at')
            ->selectRaw(self::RAW_FULL_NAME)
            ->addSelect('users.*')->getModels();
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function isInstructor()
    {
        return $this->instructor == 1;
    }

    /**
     * @return bool
     */
    public function isStudent()
    {
        return $this->instructor == 0 && $this->admin == 0;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->admin == 1;
    }

    /**
     * @return string
     */
    public function role()
    {
        if ($this->isAdmin()) return 'admin';
        elseif ($this->isInstructor()) return 'instructor';
        else return 'student';
    }


    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'email';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->email;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this[$this->getRememberTokenName()] = $value;
        $this->save();
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return !empty($this->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        try {
            $datetime = (new Carbon('now', config('app.timezone')))->format(config('constants.date.stamp'));
        } catch (\Exception $e) {
            $datetime = date(config('constants.date.stamp'));
        }
        $this->email_verified_at = $datetime;
        $this->save();
        return true;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        if (config('env.APP_ENV', 'local') == 'testing') {
            $mailer = new \App\Notifications\AppVerifyEmail($this);
            Mail::to($this->email)->send($mailer);
        }
        clock()->info("Sent email verification to: {$this->email}");
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }

    /**
     * Get the password_reset token
     * @return string the token
     */
    public function getPasswordResetToken()
    {
        return DB::table('password_resets')
            ->where('email', $this->email)
            ->orderBy('created_at', 'DESC')
            ->first()->token;
    }

    /**
     * Generates and stores a token in the password_resets table
     * @return string|false
     * @throws \Throwable If the environment is in DEBUG mode
     */
    public function generatePasswordResetToken()
    {
        $token = Hash::make($this->email . ':' . $this->lname . ':' . time());
        if (!DB::table('password_resets')->insert(['email' => $this->getEmailForPasswordReset(), 'token' => $token])) {
            throw_if(config('env.APP_DEBUG', false), new QueryException(sprintf('%s [Values: email:"%s",token:"%s"]', 'Error inserting token into password_resets.', $this->email, $token)));
            return false;
        }
        return $token;
    }

    /**
     * Determine if the entity has a given ability.
     *
     * @param string $ability
     * @param array|mixed $arguments
     * @return bool
     */
    public function can($ability, $arguments = [])
    {
        if ($this->isAdmin()) return true;

        if (array_key_exists('form', $arguments) && $arguments['form'] instanceof Form) {
            $cid = $arguments['form']->session()->first()->course_id;
        }
        if (array_key_exists('form', $arguments) && $arguments['form'] instanceof FormTemplate) {
            return $this->isInstructor();
        }
        if (array_key_exists('course', $arguments) && $arguments['course'] instanceof Course) {
            $cid = $arguments['course']->id;
        }
        if (array_key_exists('group', $arguments) && $arguments['group'] instanceof Group) {
            $cid = $arguments['group']->session()->first()->course_id;
        }
        if (array_key_exists('cid', $arguments)) {
            $cid = $arguments['cid'];
        }
        if (array_key_exists('session', $arguments) && $arguments['session'] instanceof Session) {
            $cid = $arguments['session']->course_id;
        }
        if (array_key_exists('user', $arguments) && $arguments['user'] instanceof User) {
            $user = $arguments['user'];
        }
        if (array_key_exists('student', $arguments) && $arguments['student'] instanceof User) {
            $user = $arguments['student'];
        }

        switch ($ability) {
            case 'session.feedback':
                return isset($user) && isset($cid) &&
                    $this->isInstructor() && $this->ownsCourse($cid) &&
                    StudentSession::whereUserId($user->id)->exists();
            case 'session.fillin':
            case 'session.fill':
                return isset($cid) && $this->isStudent()
                    && $this->studentCourses()->where('courses.id', '=', $cid)
                    && !StudentSession::whereUserId(Auth::user()->id)->where('session_id', '=', $arguments['session']->id)->exists();
            case 'session.addGroup':
            case 'session.joinGroup':
                return isset($cid) && $this->isStudent()
                    && !StudentGroup::whereUserId(Auth::user()->id)
                        ->whereIn('group_id', Group::whereSessionId($arguments['session']->id)->get(['id']))
                        ->exists();
            case 'user.show':
                return (!Auth::user()->isStudent() || Auth::user()->id == $user->id) && isset($user) && !$user->isAdmin();
            case 'user.home':
            case 'verification.notice':
            case 'user.profile':
            case 'course.index':
            case 'session.index':
            case 'session.active':
                return true;
            case 'course.view':
            case 'course.create':
            case 'course.store':
            case 'session.store':
            case 'form.create':
            case 'form.store':
            case 'session.create':
            case 'form.index':
            case 'form.preview':
            case 'user.config':
                return $this->isInstructor();
            case 'course.update':
            case 'course.edit':
            case 'course.destroy':
            case 'course.copy':
            case 'course.duplicate':
            case 'course.delete':
            case 'course.trash':
            case 'course.add-student':
            case 'session.view':
            case 'session.show':
            case 'session.mark':
            case 'group.show':
            case 'group.storeMark':
            case 'session.update':
            case 'session.edit':
            case 'form.update':
            case 'form.edit':
            case 'session.delete':
            case 'course.students':
            case 'form.view':
            case 'form.duplicate':
            case 'form.delete':
            case 'form.trash':
            case 'course.disenroll':
                return isset($cid) && ($cid == \App\Course::DUMMY_ID || ($this->isInstructor() && $this->ownsCourse($cid)));
            case 'config.store':
            default:
                return false;
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param $token
     * @return void
     * @throws \Throwable
     */
    public function sendPasswordResetNotification($token)
    {
        $this->password_reset_token = $token;
        try {
            if (config('env.APP_ENV', 'local') == 'testing') {
                $mailer = new AppResetPasswordEmail($this);
                Mail::to($this->email)->send($mailer);
            }
            clock()->info("AppResetPasswordEmail sent to {$this->email}");
        } catch (\Throwable $e) {
            clock()->info("Failed to send AppResetPasswordEmail to {$this->email}", ['trace' => true]);
            throw_if(config('env.APP_DEBUG', false), $e);
        }
    }

    /**
     * Send an invitational email to the newly created student / user, regarding the specified course.
     * @param Course $course
     * @return void
     */
    public function sendStudentInvitationEmail(Course $course)
    {
        $this->password = $this->generateStudentPassword();
        $this->save();
        if (config('env.APP_ENV', 'local') == 'testing') {
            $mailer = new StudentInviteEmail($this, $course);
            Mail::to($this->email)->send($mailer);
        }
        clock()->info("StudentInviteEmail sent to {$this->email}");
    }

    /**
     * Send an enrollment email to the current user, regarding the specified course.
     * @param Course $course
     * @return void
     */
    public function sendEnrollmentEmail(Course $course)
    {
        if (config('env.APP_ENV', 'local') == 'testing') {
            $mailer = new StudentEnrollEmail($this, $course);
            Mail::to($this->email)->send($mailer);
        }
        clock()->info("StudentEnrollEmail sent to {$this->email}");
    }

    /**
     * Generates a new password for the student.
     * Pattern: /[a-z]{3}[0-9]{3}[a-z]{3}/i
     * @return string|false
     */
    public function generateStudentPassword()
    {
        try {
            $this->password_plain =
                Str::random(3) .
                random_int(100, 999) .
                Str::random(3);
            clock()->info("Generated password for user {$this->email} : {$this->password_plain}");
            return Hash::make($this->password_plain);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param string $action
     * @return string
     */
    public function verificationUrl(string $action)
    {
        return URL::temporarySignedRoute(
            'user.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', \config('auth.password_reset.expire'))),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
                'action' => $action,
            ]
        );
    }

    /**
     * @return String The generate API token
     */
    public function generateApiToken()
    {
        $this->api_token = hash('sha256', Str::random(80));
        $this->save();
        return $this->api_token;
    }

    /**
     * Retrieve the students of the instructor.
     * @return mixed
     */
    public function enrolled()
    {
        $courses = array_column($this->courses()->get()->toArray(), 'id');
        $query = DB::table('users')
            ->join('student_course', 'users.id', '=', 'user_id')
            ->whereIn('student_course.course_id', $courses);
        return $query->get();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $session_id
     * @param string $type
     * @return float|int
     */
    public function getMarkFactor($session_id, $type = 'r')
    {
        $self = Review::whereRecipientId($this->getId())
            ->where('sender_id', '=', $this->getId())
            ->where('session_id', '=', $session_id)
            ->where('type', '=', $type) // criteria
            ->sum('mark');

        $total = Review::whereSenderId($this->getId())
            ->where('session_id', '=', $session_id)
            ->where('type', '=', $type) // criteria
            ->sum('mark');

        $teammates = array_column($this->teammates()->toArray(), 'id');
//        $team = array_push($teammates, $this->id);

        $submitted = array_column(StudentSession::whereSessionId($session_id)
            ->whereIn('user_id', $teammates)
            ->get(['user_id'])->toArray(), 'user_id');

        $not_submitted = array_filter($teammates, function ($id) use ($submitted) {
            return !in_array($id, $submitted);
        });

        if (empty($not_submitted) && $total > 0) { // everyone submittedStudentEnrollEmail
            return round(floatval($self / $total), 2, PHP_ROUND_HALF_DOWN);
        } elseif ($total == 0) { // this student did not submit
            return 0;
        } else { // calculate with fudge factor
            $factor = round(floatval($self / $total), 2, PHP_ROUND_HALF_DOWN);
            return round($factor * floatval(\config('mark.fudge')), 2);
        }
    }

    /**
     * @param $session_id
     * @param string $type
     * @return int
     * @throws \Throwable
     */
    public function calculateMark($session_id, $type = 'r')
    {
        $group_mark = $this->group()->mark;

        if ($group_mark == 0)
            throw new NotFoundHttpException("This group has not been marked yet");

        // [e]valuation (0-5), crite[r]ion (0-100)
        $total_factor = $this->getMarkFactor($session_id, 'r') + $this->getMarkFactor($session_id, 'e');
        $group_weight = floatval(\config('env.GROUP_WEIGHT'));
        $mark = ($group_weight * $group_mark) + ($total_factor * ($group_weight * $group_mark));
        $mark = $mark > 100 ? 100 : $mark;

        $studentSession = \App\StudentSession::whereUserId($this->id)->where('session_id', '=', $session_id)->firstOrFail();
        $studentSession->mark = $mark;
        $studentSession->saveOrFail();

        return $mark;
    }

    /**
     * @param int $session_id
     * @return int
     */
    public function getIndividualMark($session_id)
    {
        return $this->studentSessions()->where('session_id', '=', $session_id)
            ->first()->mark;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        if ($this->isStudent())
            return 'Student';
        elseif ($this->isAdmin())
            return 'Administrator';
        else
            return 'Instructor';
    }
}
