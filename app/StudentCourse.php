<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StudentCourse
 *
 * @property int $user_id
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentCourse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentCourse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentCourse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentCourse whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentCourse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentCourse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentCourse whereUserId($value)
 * @mixin \Eloquent
 */
class StudentCourse extends Model
{
    protected $table = 'student_course';
    public $incrementing = false;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'user_id' => 1,
        'course_id' => 1,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'course_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'int',
        'course_id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
