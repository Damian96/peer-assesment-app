<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StudentSession
 *
 * @property int $user_id
 * @property int $session_id
 * @property int $mark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentSession whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentSession whereUserId($value)
 * @mixin \Eloquent
 */
class StudentSession extends Model
{
    protected $table = 'student_session';
    public $incrementing = true;
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id', 'session_id', 'mark'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $casts = [
        'user_id' => 'int',
        'session_id' => 'int',
        'mark' => 'int',
    ];

    protected $attributes = [
        'user_id' => null,
        'session_id' => null,
        'mark' => null,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function group()
    {
        return $this->hasOneThrough(\App\Group::class, \App\StudentGroup::class,
            'user_id', 'id', 'user_id', 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function session()
    {
        return $this->hasOne(\App\Session::class, 'id', 'session_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne(\App\User::class, 'id', 'user_id');
    }
}
