<?php

namespace App;

use App\Notifications\AppVerifyEmail;
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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereInstructor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRegNum($value)
 * @method static whereApiToken(string $token)
 */
class User extends Model implements Authenticatable, MustVerifyEmail, CanResetPassword, Authorizable
{
    use Notifiable;
    use Resettable;
    use SendsPasswordResetEmails;

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
        'email', 'password', 'fname', 'lname', 'reg_num', 'department', 'instructor', 'admin'
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
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'instructor' => 'int',
        'admin' => 'int',
    ];

    /**
     * @see https://www.php.net/manual/en/language.oop5.overloading.php#object.set
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        switch ($key) {
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
            case 'registration_date': # Alias of 'registered_at'
                try {
                    $mutable = new Carbon($this->created_at, config('app.timezone'));
                    return $mutable->format(Config::get('constants.date.full'));
                } catch (\Exception $e) {
                    return $this->created_at;
                }
            case 'last_login':
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
                if ($this->isInstructor()) return 'Instructor';
                elseif ($this->admin == 1) return 'Admin';
                else return 'Student';
            case 'crumbs':
                return empty($this->crumbs) ? [] : $this->crumbs;
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function group()
    {
        return $this->hasOne('\App\StudentGroup', 'user_id', 'id');
    }

    /**
     * Get the student's teammates from the database
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough|false
     */
    public function teammates()
    {
        return $this->group()->exists() ? $this->group()->first()->students() : false;
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
//            return $this->hasManyThrough('\App\Course', '\App\StudentCourse', 'user_id', 'user_id', 'id', 'user_id');
        }
        return false;
    }

    /**
     * Check if the instructor owns the specified course
     * @param int $id
     * @return bool
     */
    public function ownsCourse(int $id)
    {
        return $this->courses()->get()->toBase()->contains('id', '=', $id);
    }

    /**
     * Check if the student is registered in the specified course
     * @param int $id the course's ID \App\Course\::$id
     * @return bool
     */
    public function isRegistered(int $id)
    {
        return $this->studentCourses()->get()->toBase()->contains('id', '=', $id);
    }

    /**
     * Retrieve the courses that the users is registered on
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function studentCourses()
    {
        return $this->belongsToMany('\App\Course', 'student_course', 'user_id', 'course_id');
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
            ->whereNotNull('email_verified_at')
            ->get();
    }

    /**
     * Get all the instructors
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getInstructors()
    {
        return DB::table('users')
            ->select('id', DB::raw('CONCAT(SUBSTR(fname, 1, 1), ". ", lname) AS name'))
            ->where('instructor', '=', '1')
            ->whereNotNull('email_verified_at')
            ->get();
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
        $mailer = new AppVerifyEmail($this);
        Mail::to($this->email)->send($mailer);
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
        try {
            $token = $this->generatePasswordResetToken();
//            $relation = $this->passwordReset()->get();
            return $token;
        } catch (QueryException $e) {
            return false;
        }
    }

    /**
     * Generates and stores a token in the password_resets table
     * @return string
     * @throws QueryException
     */
    private function generatePasswordResetToken()
    {
        $token = Hash::make($this->email . ':' . $this->lname . ':' . time());
        if (!DB::table('password_resets')->insert(['email' => $this->getEmailForPasswordReset(), 'token' => $token])) {
            throw new QueryException(sprintf('%s [Values: email:"%s",token:"%s"]', 'Error inserting token into password_resets.', $this->email, $token));
        }
//        $this->password_reset_token = $token;
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

        switch ($ability) {
            case 'course.index':
            case 'user.profile':
//            case 'user.create':
//            case 'user.store':
            case 'user.show':
                return true;
            case 'user.home':
            case 'course.view':
            case 'course.create':
            case 'course.store':
            case 'session.store':
            case 'session.active':
            case 'form.create':
            case 'form.store':
            case 'session.create':
            case 'form.index':
                return $this->isInstructor();
//            case 'session.update':
            case 'course.update':
            case 'course.edit':
            case 'course.destroy':
            case 'course.copy':
            case 'course.duplicate':
            case 'course.delete':
            case 'course.trash':
            case 'course.add-student':
            case 'session.index':
            case 'session.view':
            case 'session.edit':
            case 'form.edit':
            case 'session.delete':
            case 'course.students':
            case 'form.view':
            case 'form.duplicate':
            case 'form.delete':
                if (array_key_exists('form', $arguments) && $arguments['form'] instanceof Form) {
                    $cid = $arguments['form']->session()->first()->course_id;
                } elseif (array_key_exists('course', $arguments) && $arguments['course'] instanceof Course) {
                    $cid = $arguments['course']->id;
                } elseif (array_key_exists('session', $arguments) && $arguments['session'] instanceof Session) {
                    $cid = $arguments['session']->course_id;
                } elseif (array_key_exists('id', $arguments)) {
                    $cid = $arguments['id'];
                } elseif (array_key_exists('cid', $arguments)) {
                    $cid = $arguments['cid'];
                } else {
                    return false;
                }
                return $this->isInstructor() && $this->ownsCourse($cid);
//                return $this->isInstructor() || ($this->isStudent() && $this->isRegistered($cid));
            default:
                return false;
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
//        $mailer = new AppResetPasswordEmail($this);
//        Mail::to($this->email)->send($mailer);
        clock()->info("StudentInviteEmail sent to {$this->fullname}");
    }

    /**
     * Send an invitational email to the newly created student / user, regarding the specified course.
     * @param Course $course
     * @return void
     */
    public function sendStudentInvitationEmail(Course $course)
    {
        $this->password = $this->generateStudentPassword();
//        $mailer = new StudentInviteEmail($this, $course);
//        Mail::to($this->email)->send($mailer);
        clock()->info("StudentInviteEmail sent to {$this->fullname}");
    }

    /**
     * Send an enrollment email to the current user, regarding the specified course.
     * @param Course $course
     * @return void
     */
    public function sendEnrollmentEmail(Course $course)
    {
//        $mailer = new StudentEnrollEmail($this, $course);
//        Mail::to($this->email)->send($mailer);
        clock()->info("StudentEnrollEmail sent to {$this->fullname}");
    }

    /**
     * Generates a new password for the student.
     * Pattern: /[0-9]{3}[a-z]{3}[0-9a-z]{3}/i
     * @return string|false
     */
    public function generateStudentPassword()
    {
        try {
            $this->password_plain = random_int(1000, 2000) .
                substr($this->fname, 0, 3) .
                substr($this->reg_num, -3);
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
    public function verificationUrl(String $action)
    {
        return URL::temporarySignedRoute(
            'user.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
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
}
