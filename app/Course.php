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
 * @property int $ac_year_month
 * @property string $department
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
    const SPRING = 5;
    const FALL = 9;

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
                return intval(Carbon::createFromTimestamp(strtotime($this->ac_year), config('app.timezone'))->format('Y'));
            case 'ac_year_stamp':
                return Carbon::createFromTimestamp(strtotime($this->ac_year), config('app.timezone'))->format(config('constants.date.stamp'));
            case 'ac_year_full':
                return Carbon::createFromTimestamp(strtotime($this->ac_year), config('app.timezone'))->format(config('constants.date.full'));
            case 'ac_year_month':
                return intval(Carbon::createFromTimestamp(strtotime($this->ac_year), config('app.timezone'))->format('m'));
            case 'ac_year_pair':
                $carbon = Carbon::createFromTimestamp(strtotime($this->ac_year), config('app.timezone'));
                if ($carbon->month <= 5) {
                    return ($carbon->year - 1) . '-' . substr($carbon->year, -2);
                } else {
                    return $carbon->year . '-' . substr($carbon->year + 1, -2);
                }
            case 'ac_year_arr':
                $carbon = Carbon::now(config('app.timezone'));
                if ($carbon->month <= 5) { // Spring Semester
                    return [
                        Carbon::create($carbon->year - 1, 10)->format(config('constants.date.stamp')),
                        Carbon::create($carbon->year, 5)->format(config('constants.date.stamp')),
                    ];
                } else { // Fall Semester
                    return [
                        Carbon::create($carbon->year, 10)->format(config('constants.date.stamp')),
                        Carbon::create($carbon->year + 1, 5)->format(config('constants.date.stamp')),
                    ];
                }
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'department' => 'string',
        'ac_year' => 'datetime',
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
     */
    public function copyToCurrentYear()
    {
        if ($this->copied()) return false;
        $clone = $this->refresh()->replicate(['id', 'status']);
        $now = Carbon::now(config('app.timezone'));
        if ($now->month >= 8) {
            $clone->ac_year = $now->setMonth(10)->format(config('constants.date.stamp'));
        } else {
            $clone->ac_year = $now->setMonth(1)->format(config('constants.date.stamp'));
        }
        try {
            $clone->saveOrFail();
        } catch (\Throwable $e) {
            return false;
        }
        return $clone;
    }

    /**
     * Returns whether the course has been already copied to the current academic year
     * @return bool
     */
    public function copied()
    {
        $now = Carbon::now(config('app.timezone'));
        if ($this->ac_year_month > self::FALL) $ac_year = $now->setMonth(9)->startOfMonth()->startOfDay();
        else $ac_year = $now->subYear()->setMonth(9)->startOfMonth()->startOfDay();

        return DB::table($this->table)
            ->where('code', '=', $this->code)
            ->where('status', '=', '1')
            ->where('ac_year', '>=', $ac_year->toDateString())
            ->exists();
    }

    /**
     * Retrieve the courses of the current academic year.
     * @return Model[]|\Illuminate\Database\Builder[]|array|false
     */
    public static function getCurrentYears()
    {
        $month = intval(date('m'));
        if ($month > 5) $ac_year = Carbon::createFromDate(intval(date('Y')), 9, 1)->startOfDay();
        else $ac_year = Carbon::createFromDate(intval(date('Y'))-1, 9, 1)->startOfDay();
        return self::whereStatus('1')
            ->where('ac_year', '>=', $ac_year->toDateString())
            ->whereNotNull('department')
            ->get();
    }
}
