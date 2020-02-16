<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

/**
 * Class User
 *
 * @package App
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $code
 * @property string $description
 * @property boolean $status
 * @property string $ac_year
 * @property int $ac_year_int
 * @property int $ac_year_time
 * @property int $ac_year_month
 * @property string ac_year_pair
 * @property Carbon $ac_year_carbon
 * @property string $ac_year_full
 * @property string $department
 * @property string $department_title
 * @property string $department_full
 * @property array $ac_year_arr
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder where(int $id)
 * @method static User|Model firstOrFail(int $id)
 * @method static User|Model findOrFail(int $id)
 * @mixin Illuminate\Database\Eloquent\Model
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Session[] $sessions
 * @property-read int|null $sessions_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereAcYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $students
 * @property-read int|null $students_count
 * @property int ac_year_year
 * @property string instructor_fullname
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereDepartment($value)
 */
class Course extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $connection = 'mysql';
    public $incrementing = true;
    public $perPage = 15;
    const PER_PAGE = 15;
    const SPRING = 3;
    const FALL = 9;
    const DUMMY_ID = 11;

    protected $ac_year_time = 0;
    protected $ac_year_int = 0;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'title' => 'Untitled',
        'department' => 'none',
        'description' => null,
        'ac_year' => null,
        'code' => null,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'code', 'user_id', 'ac_year', 'department'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'status'];

    /**
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function __get($key)
    {
        switch ($key) {
            case 'instructor':
            case 'user_id':
            case 'instructor_id':
                return $this->user()->getResults()->id;
            case 'instructor_name':
            case 'instructor_fullname':
            case 'instructor_fname':
            case 'instructor_lname':
                return $this->user()->getResults()->fullname;
            case 'create_date':
            case 'created_date':
            case 'creation_date':
                if (empty($this->created_at)) {
                    return 'No';
                } else {
                    return Carbon::createFromTimestamp(strtotime($this->created_at), config('app.timezone'))->format(config('constants.date.full'));
                }
            case 'update_date':
            case 'updated_date':
                if (empty($this->updated_at)) {
                    return 'No';
                } else {
                    return Carbon::createFromTimestamp(strtotime($this->updated_at), config('app.timezone'))->format(config('constants.date.full'));
                }
            case 'ac_year_int':
            case 'ac_year_time':
                return $this->ac_year_time;
            case 'ac_year_stamp':
                return $this->ac_year_carbon->format(config('constants.date.stamp'));
            case 'ac_year_full':
                return $this->ac_year_carbon->format(config('constants.date.full'));
            case 'ac_year_year':
                return $this->ac_year_carbon->year;
            case 'ac_year_month':
                return $this->ac_year_carbon->month;
            case 'ac_year_pair':
                return sprintf("%s-%s", $this->ac_year_carbon->year - 1, substr($this->ac_year_carbon->year, -2));
            case 'status_full':
                return $this->status ? 'Enabled' : 'Disabled';
            case 'department_title':
            case 'department_full':
                switch ($this->department) {
                    case 'none':
                        return 'N/A';
                    default:
                        return User::getDepartmentTitle($this->department);
                }
            default:
                return parent::__get($key);
        }
    }

    public function __set($key, $value)
    {
        switch ($key) {
            case ('ac_year_carbon' && !$value):
                unset($this->attributes['ac_year_carbon']);
                break;
            default:
                parent::__set($key, $value);
        }
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        $handler = function ($course) {
            /**
             * @var Course $course
             */
            if ($course->id == self::DUMMY_ID) return;
            $course->ac_year_time = $course->ac_year_int = $course->acYearToTimestamp($course->ac_year);
            $course->ac_year_carbon = Carbon::createFromTimestamp($course->ac_year_time, config('app.timezone'));
        };

        static::retrieved($handler);
        static::saved($handler);
    }

    /**
     * Get the user that owns the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }

    /**
     * @param $value
     * @throws \Exception
     */
//    public function setAcYearAttribute($value)
//    {
//        $this->attributes['ac_year'] = (new Carbon($value))->format('Y');
//    }

    /**
     * Alias of self::user.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function instructor()
    {
        return $this->user()->first();
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'department' => 'string',
        'ac_year' => 'string',
        'user_id' => 'int',
        'status' => 'boolean',
    ];

    /**
     * @return mixed|array
     */
    public function sessions()
    {
        return $this->hasMany('\App\Session', 'course_id', 'id');
    }

    /**
     * Retrieve the courses that the users is registered on
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany('\App\User', 'student_course', 'course_id', 'user_id');
    }

    /**
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public static function getByInstructor(int $id)
    {
        return DB::table('courses')
            ->select('*')
            ->where('user_id', $id)
            ->get();
    }

    /**
     * @return Course|false
     * @throws \Throwable
     */
    public function copyToCurrentYear()
    {
        if ($this->copied()) return false;
        $clone = $this->refresh()->replicate(['id', 'status']);
        $now = Carbon::now(config('app.timezone'));
        $clone->ac_year = self::toAcademicYear($now->timestamp);
        unset($clone->attributes['ac_year_carbon']);
        try {
            $clone->saveOrFail();
        } catch (\Throwable $e) {
            throw_if(env('APP_DEBUG', false), $e);
            return false;
        }
        return $clone;
    }

    /**
     * Returns whether the course has been already copied to the current academic year
     * @return bool
     * @throws \Throwable
     */
    public function copied()
    {
        $now = Carbon::now(config('app.timezone'));
        $ac_year = Carbon::createFromDate($this->ac_year_year, $this->ac_year_month, 1, config('app.timezone'));

        $date = null;
        if ($now->year == $ac_year->year) return true;
        elseif ($ac_year->year < $now->year) {
            if ($now->month > self::FALL && $now->month <= self::SPRING + 4)
                $date = $now->setMonth(self::FALL)->setDay(1)->startOfDay()->format(config('constants.date.stamp'));
            else
                $date = $now->setMonth(self::SPRING + 4)->setDay(1)->startOfDay()->format(config('constants.date.stamp'));
        } else {
            abort_if(env('APP_DEBUG', false), 500, "The academic year {$this->ac_year} is invalid.");
            return false;
        }

        return DB::table($this->table)
            ->where('code', '=', $this->code)
            ->where('status', '=', '1')
            ->where('ac_year', '>=', $date)
            ->whereNotIn('id', [$this->id])
            ->exists();
    }

    /**
     * Retrieve the courses of the current academic year.
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public static function getCurrentYears()
    {
        $now = Carbon::now();
        return self::whereStatus('1')
            ->where('ac_year', 'LIKE', '%-' . $now->year)
            ->whereNotNull('department');
    }

    /**
     * Format timestamp to a proper 'ac_year' [SE-YYYY]
     * @see: https://www.auth.gr/en/academic_calendar
     * @param int $time
     * @return string
     */
    public static function toAcademicYear(int $time)
    {
        $carbon = Carbon::createFromTimestamp($time, config('app.timezone'));
        if ($carbon->month <= 1 || $carbon->month >= 6) { // Fall Semester
            $se = 'FA';
            if ($carbon->month <= 2) {
                $year = $carbon->subYear()->format('Y');
            } else {
                $year = $carbon->format('Y');
            }
        } else { // Spring Semester
            $se = 'SP';
            $year = $carbon->format('Y');
        }
        return "{$se}-{$year}";
    }

    /**
     * Format the given ac_year [SE-YYYY] to a UNIX timestamp
     * @param string $ac_year
     * @param bool $end Whether to adjust the dates to the end of the semesters
     * @return int a UNIX timestamp
     * @throws \Exception
     */
    public static function acYearToTimestamp($ac_year, bool $end = false)
    {
        $year = substr($ac_year, -4);
        $se = substr($ac_year, 0, 2);
        if ($se === 'FA') { // Fall
            $carbon = Carbon::createFromDate(intval($year), $end ? 2 : 9, $end ? 14 : 1, config('app.timezone'));
        } else if ($se === 'SP') { // Spring
            $carbon = Carbon::createFromDate(intval($year), $end ? 5 : 2, $end ? 30 : 15, config('app.timezone'));
        } else {
            throw new \Exception("Not a valid academic year: {$ac_year}");
        }
        return $carbon->timestamp;
    }

    /**
     * Format the given UNIX timestamp to an academic year pair [YYYY-YY]
     * @param int $time a UNIX timestamp
     * @return string an academic year pair [YYYY-YY]
     */
    public static function toAcademicYearPair(int $time)
    {
        $ac_year = explode('-', self::toAcademicYear($time));
        $year = $ac_year[1];
        $se = $ac_year[0];
        if ($se === 'SP') $year = intval($year) - 1;
        return sprintf("%s-%s", $year, substr(intval($year) + 1, -2));
    }
}
