<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StudentGroup
 *
 * @property int $user_id
 * @property int $group_id
 * @property-read \App\Group $group
 * @property-read \App\User $student
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $students
 * @property-read int|null $students_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentGroup whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentGroup whereUserId($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StudentGroup whereUpdatedAt($value)
 */
class StudentGroup extends Model
{
    protected $table = 'user_group';
    protected $connection = 'mysql';
    public $incrementing = true;
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id', 'group_id'
    ];

    protected $casts = [
        'user_id' => 'int',
        'group_id' => 'int',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->student();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function group()
    {
        return $this->hasOne('\App\Group', 'id', 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('\App\User', 'id', 'user_id');
    }
}
