<?php

namespace Modules\Course\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payment\Entities\PaymentHistory;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\StudentQuizAnswer;
use Modules\User\Entities\Profile;
use Modules\Student\Entities\Review;
use Modules\User\Entities\StudentCourse;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'teacher_id',
        'course_category_id',
        'nature',
        'description',
        'course_image',
        'is_course_free',
        'is_handout_free',
        'price_usd',
        'discount_usd',
        'price_pkr',
        'discount_pkr',
        'total_duration',
        'is_approved',
        'approver_id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'status',
        'model_start_date',
        'model_end_date'
    ];

    protected $withCount = [
        'slots'
        , 'availableSlots'
        , 'contents'
        , 'handouts'
        , 'enrolledStudents'
        , 'quizzez'
        , 'myEnrollment'
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
        static::created(function ($model) {
            // Do something after saving
        });
        parent::boot();

        // delete a course
        static::deleting(function ($model) {
            $model->contents()->delete();
            $model->handouts()->delete();
            $model->outlines()->delete();
            $model->slots()->delete();
            $model->reviews()->delete();
            $model->queries()->delete();
            $model->enrolledStudents()->delete();
            $model->studentQuizAnswers()->delete();
        });
    }

    public function getModelStartDateAttribute()
    {
        return date('Y-m-d', strtotime($this->start_date));
    }

    public function getModelEndDateAttribute()
    {
        return date('Y-m-d', strtotime($this->end_date));
    }

    public function getStatusAttribute()
    {
        $date_now = new DateTime();
        $end_date    = new DateTime($this->end_date);

        return ($date_now > $end_date)? 'active' : 'completed';
    }

    public function teacher()
    {
        return $this->belongsTo(Profile::class, 'teacher_id', 'id')->with('user');
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id', 'id');
    }

    public function contents()
    {
        return $this->hasMany(CourseContent::class, 'course_id', 'id')->orderBy('id', 'DESC');
    }

    public function handouts()
    {
        return $this->hasMany(CourseHandout::class, 'course_id', 'id')->orderBy('id', 'DESC');
    }

    public function outlines()
    {
        return $this->hasMany(CourseOutline::class, 'course_id', 'id')->orderBy('id', 'DESC');
    }

    public function slots()
    {
        return $this->hasMany(CourseSlot::class, 'course_id', 'id')->with('course', 'lastEnrolment')->orderBy('id', 'DESC');
    }
    public function availableSlots()
    {
        return $this->hasMany(CourseSlot::class, 'course_id', 'id')->doesntHave('enrolments')->with('course', 'lastEnrolment')->orderBy('id', 'DESC');
    }

    public function enrolledStudents()
    {
        return $this->hasMany(StudentCourse::class, 'course_id', 'id')->with([
            'student'
            , 'course'
            , 'slot'
        ])
        ->orderBy('id', 'DESC');
    }

    public function lastEnrollment()
    {
        return $this->hasOne(StudentCourse::class, 'course_id', 'id')
        ->with([
            'student'
            , 'course'
            // , 'slot'
        ])
        ->orderBy('id', 'DESC');
    }

    public function myEnrollment()
    {
        return $this->hasOne(StudentCourse::class, 'course_id', 'id')->where('student_id', app('request')->user()->profile_id)
        ->orderBy('id', 'DESC');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'course_id', 'id')->with(['student', 'course'])->orderBy('id', 'DESC');
    }

    public function queries()
    {
        return $this->hasMany(StudentQuery::class, 'course_id', 'id')->with(['student', 'course', 'queryResponse'])->orderBy('id', 'DESC');
    }

    public function quizzez()
    {
        return $this->hasMany(Quiz::class, 'course_id', 'id')->whereHas('questions')->with(['slot', 'course', 'assignee'])->orderBy('id', 'DESC');
    }

    public function payments()
    {
        return $this->hasMany(PaymentHistory::class, 'payee_id', 'id')->orderBy('id', 'DESC');
    }
    public function payment()
    {
        return $this->hasOne(PaymentHistory::class, 'payee_id', 'id')->orderBy('id', 'DESC');
    }

    public function studentQuizAnswers()
    {
        return $this->hasMany(StudentQuizAnswer::class, 'course_id', 'id')->orderBy('id', 'DESC');
    }
}
