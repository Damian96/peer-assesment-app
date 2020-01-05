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
        'form_id', 'title', 'subtitle', 'data'
    ];

    protected $casts = [
        'form_id' => 'int',
        'title' => 'string',
        'subtitle' => 'string',
        'data' => 'string'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function form()
    {
        return $this->hasOne('\App\Form', 'id', 'form_id');
    }
}
