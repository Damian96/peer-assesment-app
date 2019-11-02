<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $connection = 'mysql';
    protected $table = 'password_resets';
    protected $primaryKey = 'email';
    protected $keyType = 'string';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'email' => null,
        'token' => null,
        'created_at' => null
    ];

    protected $fillable = [
        'email', 'token'
    ];
}
