<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $guarded = ['id'];

    protected $fillable = [
        'sender_id', 'recipient_id', 'question_id', 'mark', 'comment', 'answer'
    ];

    protected $casts = [
        'sender_id' => 'int',
        'recipient_id' => 'int',
        'question_id' => 'int',
        'mark' => 'int',
        'comment' => 'string',
        'answer' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sender()
    {
        return $this->hasOne('\App\User', 'id', 'sender_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function recipient()
    {
        return $this->hasOne('\App\User', 'id', 'recipient_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function question()
    {
        return $this->hasOne('\App\Question', 'id', 'question_id');
    }
}
