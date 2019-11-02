<?php

namespace App;

use App\Notifications\AppResetPasswordEmail;
use App\Notifications\AppVerifyEmail;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Auth\Passwords\CanResetPassword as Resettable;
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
 * @property string|null department
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
 */
class User extends Model implements Authenticatable, MustVerifyEmail, CanResetPassword
{
    use Notifiable;
    use Resettable;
    use SendsPasswordResetEmails;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $connection = 'mysql';

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
        'password', 'remember_token', 'verification_token'
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
     * @param string $abbr
     * @return string
     */
    public static function getDepartmentTitle(string $abbr)
    {
        switch ($abbr) {
            case 'CS':
                return 'Computer Science';
            case 'ES':
                return 'English Studies';
            case 'PSY':
                return 'Psychology Studies';
            case 'BS':
                return 'Business Administration & Economics';
            case 'MBA':
                return 'Executive MBA';
            default:
                return '-';
        }
    }

    /**
     * @param string $abbr
     * @return string
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
            default:
                return '-';
        }
    }

    /**
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
            default:
                return parent::__get($key);
        }
    }

    /**
     * Get the course record associated with the user.
     */
    public function course()
    {
        if ($this->isAdmin() || $this->isInstructor()) {
            return $this->hasOne('\App\Course');
        }
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function studentCourses()
    {
        return $this->belongsToMany('\App\Course', 'student_course', 'user_id', 'course_id');
    }

    /**
     * @param $email String The user's email.
     * @return User|Model
     */
    public static function getUserByEmail($email)
    {
        return self::where('email', $email)->first()->refresh();
    }

    /**
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
     * Get the phone record associated with the user.
     */
    public function passwordReset()
    {
        return $this->hasOne('App\PasswordReset', 'email', 'email');
    }

    /**
     * Send the password reset notification.
     *
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $mailer = new AppResetPasswordEmail($this);
        Mail::to($this->email)->send($mailer);
    }

    /**
     * Get the password_reset token
     * @return string the token
     */
    public function getPasswordResetToken()
    {
        try {
            $token = $this->generatePasswordResetToken();
            $relation = $this->passwordReset()->get();
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
        $this->password_reset_token = $token;
        return $token;
    }

    /**
     * @return bool
     */
//    public function hasPasswordResetTokenExpired() {
//        return time() > ($this->password_reset_code_created_at + config('auth.password_reset.expire'));
//    }

}
