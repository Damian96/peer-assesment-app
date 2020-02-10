<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Review
 *
 * @property int $id
 * @property int $sender_id
 * @property int $recipient_id
 * @property int $question_id
 * @property int|null $mark 0-5
 * @property string|null $comment
 * @property bool|null $answer boolean
 * @property-read \App\Question $question
 * @property-read \App\User $recipient
 * @property-read \App\User $sender
 * @property string type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Review whereSenderId($value)
 * @mixin \Eloquent
 */
class Review extends Model
{
    protected $table = 'reviews';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $guarded = ['id'];

    protected $fillable = [
        'sender_id', 'recipient_id', 'question_id', 'mark', 'comment', 'answer', 'type'
    ];

    protected $casts = [
        'sender_id' => 'int',
        'recipient_id' => 'int',
        'question_id' => 'int',
        'mark' => 'int',
        'type' => 'string',
        'comment' => 'string',
        'answer' => 'string'
    ];

    protected $attributes = [
        'recipient_id' => 0,
        'mark' => 0,
        'type' => null,
        'comment' => null,
        'answer' => null,
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
