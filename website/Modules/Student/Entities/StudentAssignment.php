<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Assignment\Entities\Assignment;
use Modules\Course\Entities\Course;
use Modules\User\Entities\Profile;

class StudentAssignment extends Model
{
    use SoftDeletes;

    protected $appends = [
        'is_marked_assignment'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'course_id',
        'student_id',
        'assignment_id',
        'media'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // delete a profile
        static::deleting(function ($model) {
            // deletes nothing
            // has no effect
        });
    }


    public function getIsMarkedAssignmentAttribute()
    {
        $model = $this->where('status', 'marked')->where('student_id', app('request')->user()->profile_id)->first();
        return (null == $model);
    }


    /**
     * get the Profile info
     */
    public function student()
    {
        return $this->belongsTo(Profile::class, 'student_id', 'id');
    }

    /**
     * get the Course info
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    /**
     * get the Assignment info
     */
    public function teacherAssignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id', 'id');
    }
}
