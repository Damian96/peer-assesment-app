<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Session
 *
 * @package App
 * @property string $table
 * @property string $primaryKey
 * @property string $connection
 * @property int $id
 * @property int $course_id
 * @property string|null $instructions
 * @property \Illuminate\Support\Carbon $deadline
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Course $course
 * @property string title
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereUpdatedAt($value)
 * @property int $session_id
 * @property-read \App\Form $form
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereOpenData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereTitle($value)
 * @method static whereNotIn(string $string, array $except)
 * @mixin \Eloquentform
 * @property string $open_date
 * @property int open_date_int
 * @property int deadline_int
 * @property int max_groups
 * @property int groups
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereOpenDate($value)
 */
class Session extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'sessions';
    protected $connection = 'mysql';

    /**
     * @param string $name
     * @return mixed|string
     */
    public function __get($name)
    {
        switch ($name) {
            case 'max_groups':
                return $this->groups;
            case 'course_title':
                return $this->course()->exists() ? $this->course()->first()->title : 'N/A';
            case 'open_date_days':
                try {
                    $date = Carbon::createFromTimestamp($this->open_date_int);
                    $now = Carbon::now();
                    if ($date->timestamp > $now->timestamp)
                        return $date->diffInDays($now) . ' days from now';
                    else
                        return false;
                } catch (\Exception $e) {
                    return false;
                }
            case 'open_date_uk':
                return Carbon::createFromTimestamp($this->open_date_int)->format(config('constants.date.uk_full'));
            case 'open_date_int':
                return Carbon::createFromFormat(config('constants.date.stamp'), $this->open_date)->timestamp;
            case 'deadline_days':
                try {
                    $date = Carbon::createFromTimestamp($this->deadline_int);
                    $now = Carbon::now();
                    if ($date->timestamp > $now->timestamp)
                        return $date->diffInDays($now) . ' days from now';
                    else
                        return false;
                } catch (\Exception $e) {
                    return false;
                }
            case 'deadline_uk':
                return Carbon::createFromTimestamp($this->deadline_int)->format(config('constants.date.uk_full'));
            case 'deadline_int':
                return Carbon::createFromFormat(config('constants.date.stamp'), $this->deadline)->timestamp;
            case 'deadline_full':
                return Carbon::createFromTimeString($this->deadline)->format(config('constants.date.full'));
            case 'title_full':
                return $this->title . ' | ' . $this->course()->first()->ac_year;
            case 'instructor_fullname':
            case 'owner_name':
            case 'owner_fullname':
                return $this->course ? $this->course->instructor_fullname : 'N/A';
            case 'owner':
            case 'owner_id':
            case 'instructor_id':
                return $this->course ? $this->course->user_id : 'N/A';
            case 'department':
            case 'department_title':
                return $this->course ? $this->course->department_title : 'N/A';
            case 'ac_year_pair':
                return $this->course ? $this->course->ac_year_pair : 'N/A';
            case 'ac_year':
                return $this->course ? $this->course->ac_year : 'N/A';
            case 'ac_year_full':
                return $this->course ? $this->course->ac_year_full : 'N/A';
            default:
                return parent::__get($name);
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

        // @TODO: add deadline_carbon, open_date_carbon
        static::retrieved(function ($session) {
            $course = $session->course()->exists() ? $session->course()->first()->getModel() : null;
            $session->course = $course;

            if ($course)
                $session->instructor = $course->user()->exists() ? $course->user()->first()->getModel() : null;
            else
                $session->instructor = null;
        });
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'course', 'instructor'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
    ];

    /**
     *
     * @var array
     */
    protected $fillable = [
        'deadline', 'course_id', 'form_id', 'instructions', 'title', 'open_date',
        'groups', 'min_group_size', 'max_group_size',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'open_date' => 'datetime',
        'deadline' => 'datetime',
        'course_id' => 'int',
        'form_id' => 'int',
        'groups' => 'int',
        'min_group_size' => 'int',
        'max_group_size' => 'int',
        'instructions' => 'string',
    ];

    /**
     * Get the associated \App\Course
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function course()
    {
        return $this->hasOne('App\Course', 'id', 'course_id');
    }

    /**
     * Get the associated \App\Form
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function form()
    {
        return $this->hasOne('\App\Form', 'session_id', 'id');
    }

    /**
     * @TODO send session notifications
     * @method void
     * @return boolean
     */
    public function sendEmailNotification()
    {
//        foreach ($this->course()->students()->get() as $student) { }
        clock()->info('Students have been notified of the opened Session!');
    }

    /**
     * Get all of the current attributes on the model, except the guarded ones.
     *
     * @return array
     */
    public function getAttributes()
    {
        return array_filter($this->attributes, function ($key) {
            return !in_array($key, $this->guarded);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Retrieve the Session's groups
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(\App\Group::class, 'session_id', 'id');
    }

    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getUserGroup(User $user)
    {
        return DB::table($this->table)
            ->join('groups', 'groups.session_id', 'sessions.id')
            ->join('user_group', 'user_group.group_id', 'groups.id')
            ->where('groups.session_id', '=', $this->id)
            ->where('user_group.user_id', '=', $user->id)->get(['groups.*'])->first();
    }

    /**
     * Return whether the current user has joined a group.
     * @param User $user
     * @return false
     */
    public function hasJoinedGroup(User $user)
    {
        return $this->groups()->exists() ? $this->groups()
            ->join('user_group', 'user_group.group_id', 'groups.id')
            ->join('users', 'users.id', 'user_group.user_id')
            ->where('users.id', '=', $user->id)->exists() : false;
    }

    /**
     * Return whether the Session has any groups
     * @return bool
     */
    public function hasGroups()
    {
        return $this->groups()->exists();
    }

    /**
     * Returns whether a new group can be added to this session
     * @return bool
     */
    public function canAddGroup()
    {
        return !$this->hasGroups() || $this->groups()->count() < $this->max_groups;
    }

    /**
     * @return bool
     */
    public function hasEnded()
    {
        return time() > $this->deadline_int;
    }

    /**
     * @return bool
     */
    public function isOpen()
    {
        return time() > $this->open_date_int;
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public static function checkForClosed()
    {
        foreach (self::whereDeadline(now()->format('Y-m-d 00:00:00'))
                     ->getModels() as $session) {
            /**
             * @var \App\Session $session
             */
            foreach ($session->groups()->getModels() as $group) {
                /**
                 * @var \App\Group $group
                 */
                foreach ($group->students()->get(['users.*']) as $student) {
                    /**
                     * @var \App\User $student
                     */
                    try {
//                        var_dump($student->calculateMark($session->id));
                    } catch (\Throwable $e) {
                        throw $e;
                    }
                }
            }
        }
    }
}
