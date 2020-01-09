<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Form
 *
 * @property int $id
 * @property int $session_id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $footnote
 * @property int|null $mark 0-100
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Question[] $questions
 * @property-read int|null $questions_count
 * @property-read \App\Session $session
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form whereFootnote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Form whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
