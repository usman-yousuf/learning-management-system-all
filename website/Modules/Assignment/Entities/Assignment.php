<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Entities\Course;
use Modules\User\Entities\Profile;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'course_id',
        'assign_id',
        'title',
        'description',
        'total_marks',
        'due_date',
        'extended_date',
        'media_1',
        'media_2',
        'media_3',
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
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function assignee()
    {
        return $this->belongsTo(Profile::class, 'assignee_id', 'id');
    }
   

}
