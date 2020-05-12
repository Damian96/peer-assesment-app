<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FormTemplate
 *
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $footnote
 * @property mixed questions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate whereFootnote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate whereQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormTemplate whereUserId($value)
 */
class FormTemplate extends Model
{
    protected $table = 'form_templates';
    protected $keyType = 'int';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $guarded = ['id'];

    protected $fillable = [
        'title', 'subtitle', 'footnote', 'questions'
    ];

    protected $casts = [
        'title' => 'string',
        'subtitle' => 'string',
        'footnote' => 'string',
        'questions' => 'array',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return void
     */
    public static function boot()
    {
        self::retrieved(function ($model) {
            /**
             * @var FormTemplate $model
             */
            $model->questions = $model->questions();

            if (is_string($model->questions)) {
                $model->setAttribute('questions', json_decode($model->questions));
                $model->questions = json_decode($model->questions);
            }
        });
        parent::boot();
    }

    /**
     * @return array|string
     */
    public function questions()
    {
        return $this->getAttribute('questions');
    }
}
