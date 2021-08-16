<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Entities\Course;
use Modules\User\Entities\Profile;
Use Module\Quiz\Entities\QuizChoice;
use Modules\Common\Entities\Notification;
use Modules\Course\Entities\CourseSlot;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = [
        'modal_due_date',
        'is_attempted',
        'can_attempt',
        // 'is_attempted_quiz'
    ];

    public $withCount = ['questions', 'attempts'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'course_id',
        'assignee_id',
        'title',
        'description',
        'total_marks',
        'type',
        'duration_mins',
        'student_count',
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
            $model->questions()->delete();
            $model->studentQuizAnswers()->delete();
            $model->attempts()->delete();
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

    public function getDescriptionAttribute($value)
    {
        $description = str_replace(array("\n", "\r"), '', $value);
        $description = str_replace("'", "", $description);
        $this->description = addslashes($description);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'ref_id', 'id')->where('ref_model_name', 'quizzez')->orderBy('id', 'DESC');
    }
    public function indirectNotifications()
    {
        return $this->hasMany(Notification::class, 'additional_ref_id', 'id')->where('additional_ref_model_name', 'quizzez')->orderBy('id', 'DESC');
    }

    public function getModalDueDateAttribute($value)
    {
        return date('M d, Y', strtotime($this->due_date));
    }

    // public function getIsAttemptedQuizAttribute()
    // {
    //     return StudentQuizAnswer::where('quiz_id', $this->id )->where('student_id', request()->user()->profile->id)->first() ?  1 : 0 ;
    // }

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

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->with('category');
    }

    public function slot()
    {
        return $this->belongsTo(CourseSlot::class, 'slot_id', 'id');
    }

    public function assignee()
    {
        return $this->belongsTo(Profile::class, 'assignee_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id', 'id')->with('choices')->orderBy('created_at', 'ASC');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttemptStats::class, 'quiz_id', 'id')->with(['student', 'course'])->orderBy('created_at', 'DESC');
    }

    public function lastAttempt()
    {
        return $this->hasOne(QuizAttemptStats::class, 'quiz_id', 'id')->with(['student', 'course'])->orderBy('created_at', 'ASC');
    }

    public function myAttempt()
    {
        return $this->hasOne(QuizAttemptStats::class, 'quiz_id', 'id')->where('student_id', app('request')->user()->profile_id)->with(['student', 'course'])
        // ->with('')
        ->orderBy('created_at', 'ASC');
    }

    public function studentQuizAnswers()
    {
        return $this->hasMany(StudentQuizAnswer::class, 'quiz_id', 'id')->orderBy('id', 'ASC');
    }

    public function lastAnswer()
    {
        return $this->hasOne(StudentQuizAnswer::class, 'quiz_id', 'id')->where('student_id', app('request')->user()->profile_id)->with(['student', 'course', 'question'])->orderBy('created_at', 'DESC');
    }
}
