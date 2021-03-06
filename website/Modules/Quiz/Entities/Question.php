<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Entities\Course;
use Modules\User\Entities\Profile;
Use Module\Quiz\Entities\QuizChoice;

class Question extends Model
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
        'creator_id',
        'body',
        'correct_answer_id',
        'correct_answer',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'correct_answer_uuid'
    ];

    protected static function boot()
    {
        static::created(function ($model) {
            // Do something after saving
        });
        parent::boot();
        // delete a query
        static::deleting(function ($model) {
            $model->choices()->delete();
            $model->studentQuizAnswers()->delete();
        });
    }

    public function getCorrectAnswerUuidAttribute($value)
    {
        return $this->correctChoice->uuid ?? null;
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

    public function creator()
    {
        return $this->belongsTo(Profile::class, 'creator_id', 'id');
    }

    public function choices()
    {
        return $this->hasMany(QuestionChoice::class, 'question_id', 'id')->orderBy('created_at', 'DESC');
    }

    public function correctChoice()
    {
        return $this->belongsTo(QuestionChoice::class, 'id','question_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'id');
    }

    public function studentQuizAnswers()
    {
        return $this->hasMany(StudentQuizAnswer::class, 'question_id', 'id')->orderBy('id', 'DESC');
    }
}
