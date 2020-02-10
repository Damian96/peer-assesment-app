<?php

namespace App;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

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
