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
 * @property array $data
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
     * @FIXME getEager() refreshes records from database in contrast to get()
     * @FIXME getAttribute() retrieves casted 'data' instead of $this->attribute['data'] or $this->data
     * @param string $name
     * @return mixed|void
     */
    public function __get($name)
    {
        $data = (object)$this->getAttribute('data');
//        return dd($data);
        switch ($name) {
            case 'type':
                return $data->type;
            case 'max':
                return $data->max;
            case 'minlbl':
                return $data->minlbl;
            case 'maxlbl':
                return $data->maxlbl;
            case 'choices':
                return $data->choices;
//            case 'data':
//                return $this->data;
            default:
                return parent::__get($name);
        }
    }

//    /**
//     * @return void
//     */
//    public static function boot()
//    {
////        self::retrieved(function ($model) {
////            $model->data = json_decode($model->data);
////        });
//        parent::boot();
//    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function form()
    {
        return $this->hasOne('\App\Form', 'id', 'form_id');
    }
}
