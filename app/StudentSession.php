<?php

namespace App;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

/**
 * App\StudentSession
 *
 * @property int $user_id
 * @property int $session_id
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
    use HasCompositePrimaryKey;

    protected $table = 'student_session';
    public $incrementing = false;
    protected $primaryKey = ['user_id', 'session_id'];

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id', 'session_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $casts = [
        'user_id' => 'int',
        'session_id' => 'int',
    ];

    protected $attributes = [
        'user_id' => null,
        'session_id' => null,
    ];
}
