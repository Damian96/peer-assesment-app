<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $guarded = ['id'];

    protected $fillable = [
        'form_id', 'description', 'type'
    ];

    protected $casts = [
        'form_id' => 'int',
        'description' => 'string',
        'type' => 'string'
    ];
}
