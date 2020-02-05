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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function session()
    {
        return $this->hasOne('\App\Session', 'id', 'session_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function students()
    {
        return $this->hasManyThrough('\App\User', '\App\StudentGroup', 'user_id', 'group_id', 'id', 'id');
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
