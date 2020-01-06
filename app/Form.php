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
        'session_id', 'title', 'subtitle', 'mark'
    ];

    protected $casts = [
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany('\App\Question', 'form_id', 'id');
    }
}
