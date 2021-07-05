<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Entities\Course;
use Modules\User\Entities\Profile;
Use Module\Quiz\Entities\QuizChoice;
use Modules\Course\Entities\CourseSlot;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = ['modal_due_date', 'is_attempted', 'is_attempted_quiz'];

    public $withCount = ['questions'];

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

    public function getModalDueDateAttribute($value)
    {
        return date('M d, Y', strtotime($this->due_date));
    }

    public function getIsAttemptedQuizAttribute()
    {
        return StudentQuizAnswer::where('quiz_id', $this->id )->where('student_id', request()->user()->profile->id)->first() ?  1 : 0 ;
    }

    public function getIsAttemptedAttribute($value)
    {
        return ($this->myAttempt != null);
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
        return $this->hasOne(QuizAttemptStats::class, 'quiz_id', 'id')->where('student_id', app('request')->user()->profile_id)->with(['student', 'course'])->orderBy('created_at', 'ASC');
    }

    public function studentQuizAnswers()
    {
        return $this->hasMany(StudentQuizAnswer::class, 'quiz_id', 'id')->orderBy('id', 'ASC');
    }

    public function attemptsStats()
    {
        return $this->hasMany(QuizAttemptStats::class, 'quiz_id', 'id')->orderBy('created_at', 'ASC');
    }

}
