<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorConfig extends Model
{
    protected $table = 'instructor_configs';
    protected $keyType = 'int';
    protected $connection = 'mysql';
    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $guarded = ['id'];

    protected $fillable = ['fudge_factor', 'group_weight'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'id' => 'int',
        'fudge_factor' => 'float',
        'group_weight' => 'float'
    ];

    protected $attributes = [
        'fudge_factor' => 1.25,
        'group_weight' => .5
    ];
}
