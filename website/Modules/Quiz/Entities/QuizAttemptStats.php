<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\Entities\Notification;
use Modules\Course\Entities\Course;
Use Modules\Quiz\Entities\Quiz;
use Modules\User\Entities\Profile;

class QuizAttemptStats extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'quiz_attempt_stats';

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
        'total_questions',
        'total_marks',
        'marks_per_question',
        'total_correct_answers',
        'total_wrong_answers',
        'status',
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

            $model->notifications()->delete();
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

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'ref_id', 'id')->where('ref_model_name', 'quiz_attempt_stats')->orderBy('id', 'DESC');
    }

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
        return $this->belongsTo(Course::class, 'course_id', 'id')->with('category');
    }

}
