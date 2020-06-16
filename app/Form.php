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
 * @method static find(int $int)
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

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'session_id' => Course::DUMMY_ID,
        'title' => null,
        'subtitle' => null,
        'mark' => 0,
    ];

    protected $casts = [
        'session_id' => 'int',
        'title' => 'string',
        'subtitle' => 'string',
        'mark' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        self::retrieved(function ($model) {
            /**
             * @var \App\Session model
             */
            if ($model->session() instanceof Session && $model->session()->exists) {
                $model->session = $model->session();
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function session()
    {
        return $this->hasOne(\App\Session::class, 'id', 'session_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(\App\Question::class, 'form_id', 'id');
    }

    /**
     * Get all of the models from the database.
     *
     * @param array|mixed $columns
     * @return \Illuminate\Support\Collection
     */
    public static function all($columns = ['*'])
    {
        return self::query()->where('id', '!=', Course::DUMMY_ID)->get($columns)->collect();
    }

    /**
     * @param string[] $columns
     * @return \Illuminate\Support\Collection
     */
    public static function trashed($columns = ['*'])
    {
        return self::query()->where('session_id', '=', Course::DUMMY_ID)->get($columns)->collect();
    }
}
