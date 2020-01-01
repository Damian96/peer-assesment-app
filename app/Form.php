<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'forms';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $guarded = ['id'];

    protected $fillable = [
        'session_id', 'name', 'mark'
    ];

    protected $casts = [
        'session_id' => 'int',
        'name' => 'string',
        'mark' => 'int'
    ];
}
