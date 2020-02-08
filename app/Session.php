<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
 * @mixin \Eloquent
 * @property int $session_id
 * @property string $open_data
 * @property-read \App\Form $form
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereOpenData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereTitle($value)
 * @method static whereNotIn(string $string, array $except)
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
            case 'course_title':
                return $this->course()->exists() ? $this->course()->first()->title : 'N/A';
            case 'deadline_full':
                return Carbon::createFromTimeString($this->deadline, config('app.timezone'))->format(config('constants.date.full'));
            case 'title_full':
                return $this->title . ' | ' . $this->course()->first()->ac_year;
            case 'instructor_fullname':
            case 'owner_name':
            case 'owner_fullname':
                return $this->course()->exists() ? $this->course()->first()->getModel()->instructor_fullname : 'N/A';
            case 'owner':
            case 'owner_id':
            case 'instructor_id':
                return $this->course()->exists() ? $this->course()->first()->getModel()->user_id : 'N/A';
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

        static::retrieved(function ($session) {
            $course = $session->course()->exists() ? $session->course()->first()->getModel() : null;
            $session->course = $course;
            $session->instructor = $course->user()->exists() ? $course->user()->first()->getModel() : null;
        });
    }

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
//        'status' => 0,
    ];

    /**
     *
     * @var array
     */
    protected $fillable = [
        'deadline', 'course_id', 'form_id', 'instructions', 'title'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deadline' => 'datetime',
//        'ac_year' => 'datetime',
//        'status' => 'boolean',
        'course_id' => 'int',
        'form_id' => 'int',
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
}
