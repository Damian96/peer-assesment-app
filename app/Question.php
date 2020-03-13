<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Question
 *
 * @property int $id
 * @property int $form_id
 * @property string $title
 * @property string|null $subtitle
 * @property object $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Form $form
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Question whereUpdatedAt($value)
 * @method static \App\Question findOrFail($id)
 * @method void fill(array $array)
 * @method static find(int|string $i)
 * @mixin \Illuminate\Database\Eloquent
 */
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
        'data' => 'array'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @param array $data
     * @return array
     */
    public static function extractData(array $data)
    {
        return [
            'type' => isset($data['type']) ? $data['type'] : null,
            'choices' => isset($data['choices']) ? $data['choices'] : null,
        ];
    }

    /**
     * @param string $name
     * @return mixed|void
     */
    public function __get($name)
    {
        switch ($name) {
            case 'type':
                return $this->data->type;
            case 'choices':
                return $this->data->choices;
            case 'data':
                return $this->attributes['data'];
            default:
                return parent::__get($name);
        }
    }

    /**
     * @return void
     */
    public static function boot()
    {
        self::retrieved(function ($model) {
            /**
             * @var Question $model
             */
            if (is_string($model->data))
                $model->attributes['data'] = $model->getDataAttribute();
        });
        parent::boot();
    }

    /**
     * @return object
     */
    public function getDataAttribute()
    {
        while (is_string($this->attributes['data']))
            $this->attributes['data'] = json_decode($this->attributes['data']);
        return $this->attributes['data'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function form()
    {
        return $this->hasOne('\App\Form', 'id', 'form_id');
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!is_string($this->attributes['data']))
            $this->attributes['data'] = json_encode($this->attributes['data']);

        return parent::save($options);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
       return $this->hasMany(\App\Review::class, 'question_id', 'id');
    }
}
