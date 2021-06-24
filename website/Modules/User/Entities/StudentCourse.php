<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\CourseSlot;
use Modules\Payment\Entities\PaymentHistory;

class StudentCourse extends Model
{
    use SoftDeletes;

    protected $table = "student_courses";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'course_id',
        'student_id',
        'parent_id',
        'status',
        'joining_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'joining_date' => 'datetime',
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

    /**
     * get the Prent Profile info
     */
    public function parent()
    {
        return $this->belongsTo(Profile::class, 'parent_id', 'id');
    }

    /**
     * get the Profile info
     */
    public function student()
    {
        return $this->belongsTo(Profile::class, 'student_id', 'id')->with('parent');
    }

    /**
     * get the Course info
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->with(['category', 'teacher']);
    }

    /**
     * get the Course info
     */
    public function slot()
    {
        return $this->belongsTo(CourseSlot::class, 'slot_id', 'id')->with('lastEnrolment');
    }

    public function payment()
    {
        return $this->hasOne(PaymentHistory::class, 'id', 'ref_id')->where('ref_model_name', 'student_courses');
    }
}
