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
 * @property int $ac_year
 * @property int $id
 * @property int $course_id
 * @property bool $status
 * @property string|null $instructions
 * @property \Illuminate\Support\Carbon $deadline
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Course $course
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
            case 'deadline_full':
                return Carbon::createFromTimeString($this->deadline, config('app.timezone'))->format(config('constants.date.full'));
            case 'ac_year':
                return Carbon::createFromTimestamp($this->ac_year, config('app.timezone'))->format('Y');
            default:
                return parent::__get($name);
        }
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deadline' => 'datetime',
        'ac_year' => 'datetime',
        'status' => 'boolean',
        'course_id' => 'int',
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
}
