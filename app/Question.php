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
