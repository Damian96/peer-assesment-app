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

    /**
     * @param array $data
     * @return array
     */
    public static function extractData(array $data)
    {
        return [
            'type' => isset($data['type']) ? $data['type'] : null,
            'max' => isset($data['max']) ? $data['max'] : null,
            'minlbl' => isset($data['minlbl']) ? $data['minlbl'] : null,
            'maxlbl' => isset($data['maxlbl']) ? $data['maxlbl'] : null,
            'choices' => isset($data['choices']) ? $data['choices'] : null,
        ];
    }

    /**
     * @FIXME getEager() refreshes records from database in contrast to get()
     * @FIXME getAttribute() retrieves casted 'data' instead of $this->attribute['data'] or $this->data
     * @param string $name
     * @return mixed|void
     */
    public function __get($name)
    {
        switch ($name) {
            case 'type':
                return $this->data->type;
            case 'max':
                return $this->data->max;
            case 'minlbl':
                return $this->data->minlbl;
            case 'maxlbl':
                return $this->data->maxlbl;
            case 'choices':
                return $this->data->choices;
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
            if (!is_array($model->data))
                $model->data = $model->getDataAttribute();
        });
        parent::boot();
    }

    /**
     * Fixes JSON decoding errors
     * @return object
     */
    public function getDataAttribute()
    {
        while (!is_array($this->attributes['data'])) {
            $this->attributes['data'] = json_decode($this->attributes['data'], true);
        }
        $data = count($this->attributes['data']) == 1 ? current($this->attributes['data']) : $this->attributes['data'];
        $r = [];
        foreach ($data as $key => $value)
            $r[trim($key)] = $value;
        return (object)$r;
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
        if (is_array($this->attributes['data']))
            $this->attributes['data'] = json_encode($this->attributes['data']);

        return parent::save($options);
    }
}
