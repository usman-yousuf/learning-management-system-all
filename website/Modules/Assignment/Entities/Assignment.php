<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\Entities\Notification;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\CourseSlot;
use Modules\Student\Entities\StudentAssignment;
use Modules\User\Entities\Profile;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'is_uploaded_assignment',
        'model_media_1',
        'modal_due_date',
        'is_attempted',
        'can_attempt',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'uuid',
        'course_id',
        'slot_id',
        'assignee_id',
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

            $model->notifications()->delete();
            $model->indirectNotifications()->delete();
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
    public function getDescriptionAttribute()
    {
        $description = str_replace(array("\n", "\r"), '', $this->description);
        $description = str_replace("'", "", $description);
        $this->description = addslashes($description);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'ref_id', 'id')->where('ref_model_name', 'assignments')->orderBy('id', 'DESC');
    }
    public function indirectNotifications()
    {
        return $this->hasMany(Notification::class, 'additional_ref_id', 'id')->where('additional_ref_model_name', 'assignments')->orderBy('id', 'DESC');
    }

    public function getIsUploadedAssignmentAttribute()
    {
        return ($this->uploadAssignment != null) ;
    }
    public function getModelMedia1Attribute()
    {
        return getFileUrl($this->media_1, null, 'assignment');
    }
    public function getModalDueDateAttribute($value)
    {
        return date('M d, Y', strtotime($this->due_date));
    }
    public function getIsAttemptedAttribute($value)
    {
        return ($this->myAttempt != null);
    }

    public function getCanAttemptAttribute($value)
    {
        $dueDate = strtotime($this->due_date);
        $now = strtotime('now');
        return ($dueDate >= $now);
    }
    public function myAttempt()
    {
        return $this->hasOne(StudentAssignment::class, 'assignment_id', 'id')->where('student_id', app('request')->user()->profile_id)->with(['student', 'course'])
        // ->with('')
        ->orderBy('created_at', 'ASC');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->with('myEnrollment');
    }

    public function slot()
    {
        return $this->belongsTo(CourseSlot::class, 'slot_id', 'id');
    }

    public function assignee()
    {
        return $this->belongsTo(Profile::class, 'assignee_id', 'id');
    }

    public function uploadAssignment()
    {
        return $this->hasOne(StudentAssignment::class, 'assignment_id', 'id');
    }






}
