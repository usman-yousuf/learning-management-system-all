<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Entities\Course;
Use Modules\Quiz\Entities\Quiz;
use Modules\User\Entities\Profile;

class StudentQuizAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_quiz_answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'student_id',
        'course_id',
        'quiz_id',
        'question_id',
        'answer_body',
        'selected_answer_id',
        'status'
    ];

    protected static function boot()
    {
        static::created(function ($model) {
            // Do something after saving
        });
        parent::boot();

        static::updated(function ($model) {
        });
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

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Profile::class, 'student_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

}
