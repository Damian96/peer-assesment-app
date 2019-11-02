<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class User
 *
 * @package App
 * @property int $id
 * @property string $title
 * @property string $code
 * @property string $description
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder where(int $id)
 * @method static User|Model firstOrFail(int $id)
 * @method static User|Model findOrFail(int $id)
 * @mixin Illuminate\Database\Eloquent\Model
 */
class Course extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $connection = 'mysql';
    public $incrementing = true;
    public $perPage = 10;
    const PER_PAGE = 10;

    protected $user_id;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'title' => 'Untitled',
        'description' => null,
        'code' => null,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'code', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'user_id' => 'int',
    ];

    /**
     * Course constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        switch ($key) {
            case 'instructor':
                try {
                    $user = $this->user()->getQuery()->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    return 'N/A';
                }
                return $user->getModel()->id;
            case 'instructor_name':
            case 'instructor_fullname':
            case 'instructor_fname':
            case 'instructor_lname':
                try {
                    $user = $this->user()->getQuery()->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    return 'N/A';
                }
                return $user->getModel()->fullname;
            case 'create_date':
            case 'created_date':
            case 'creation_date':
                if (empty($this->created_at)) {
                    return 'No';
                } else {
                    return Carbon::createFromTimestamp(strtotime($this->created_at), config('app.timezone'))->format(config('constants.date.full'));
                }
            case 'update_date':
            case 'updated_date':
                if (empty($this->updated_at)) {
                    return 'No';
                } else {
                    return Carbon::createFromTimestamp(strtotime($this->updated_at), config('app.timezone'))->format(config('constants.date.full'));
                }
            default:
                return parent::__get($key);
        }
    }

    /**
     * Get the user that owns the course.
     *
     * @return  BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\User', 'user_id', 'id');
//        return $this->belongsTo('\App\User');
    }

    /**
     * @return mixed|array
     */
    public function sessions()
    {
        return $this->hasMany('\App\Session', 'id', 'course_id');
    }

    /**
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public static function getByInstructor(int $id)
    {
        return DB::table('courses')
            ->select('*')
            ->where('user_id', $id)
            ->get();
    }
}
