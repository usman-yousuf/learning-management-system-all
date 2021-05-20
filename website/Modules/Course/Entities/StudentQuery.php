<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Entities\Profile;

class StudentQuery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "queries";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'course_id',
        'student_id',
        'body',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        static::created(function ($model) {
            // Do something after saving
        });
        parent::boot();

        // delete a query
        static::deleting(function ($model) {
            $model->queryResponses()->delete();
        });
    }


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->with('category');
    }

    public function student()
    {
        return $this->belongsTo(Profile::class, 'student_id', 'id');
    }

    public function queryResponses()
    {
        return $this->hasMany(QueryResponse::class, 'query_id', 'id')->orderBy('id', 'DESC');
    }

    public function queryResponse()
    {
        return $this->hasOne(QueryResponse::class, 'query_id', 'id')->orderBy('id', 'DESC');
    }
}
