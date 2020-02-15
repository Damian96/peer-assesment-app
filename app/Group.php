<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Group
 *
 * @property int $id
 * @property int $session_id
 * @property string $name
 * @property int|null $mark
 * @property-read \App\Session $session
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereSessionId($value)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $students
 * @property-read int|null $students_count
 * @property string title
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model
{
    protected $table = 'groups';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $guarded = ['id'];

    protected $fillable = [
        'id', 'session_id', 'name', 'mark'
    ];

    protected $casts = [
        'id' => 'int',
        'session_id' => 'int',
        'name' => 'string',
        'mark' => 'int'
    ];

    public function __get($key)
    {
        switch ($key) {
            case 'title':
                return $this->getAttribute('name');
            default:
                return parent::__get($key);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function session()
    {
        return $this->hasOne('\App\Session', 'id', 'session_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function students()
    {
        return $this->hasManyThrough('\App\User', '\App\StudentGroup');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function users()
    {
        return DB::table('users')
            ->join('student_course', 'student_course.user_id', 'users.id')
            ->whereIn('users.id', $this->students()->get())
            ->get(['users.*']);
    }
}
