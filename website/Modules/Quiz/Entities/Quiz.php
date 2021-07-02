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
    protected $appends = ['modal_due_date'];

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
    public function question()
    {
        return $this->hasOne(Question::class, 'quiz_id', 'id')->orderBy('created_at', 'ASC');
    }

    public function studentQuizAnswers()
    {
        return $this->hasMany(StudentQuizAnswer::class, 'quiz_id', 'id')->orderBy('id', 'ASC');
    }
}
