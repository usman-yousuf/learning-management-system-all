<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Entities\Profile;
use Modules\Student\Entities\Review;
use Modules\User\Entities\StudentCourse;

class PaymentHistory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'amount',
        'ref_model_name',
        'ref_id',
        'additional_ref_model_name',
        'additional_ref_id',
        'stripe_trans_id',
        'stripe_trans_status',
        'card_id',
        'easypaisa_trans_id',
        'easypaisa_trans_status',
        'payment_method',
        'payee_id',
        'status',
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
            // $model->contents()->delete();
            // $model->handouts()->delete();
            // $model->outlines()->delete();
            // $model->slots()->delete();
            // $model->reviews()->delete();
            // $model->queries()->delete();
            // $model->enrolledStudents()->delete();
        });
    }

    public function ref_model_name()
    {
        return $this->belongsTo(Course::class, 'teacher_id', 'id')->with('user');
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
        return $this->hasMany(CourseSlot::class, 'course_id', 'id')->orderBy('id', 'DESC');
    }

    public function enrolledStudents()
    {
        return $this->hasMany(StudentCourse::class, 'course_id', 'id')->orderBy('id', 'DESC');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'course_id', 'id')->orderBy('id', 'DESC');
    }

    public function queries()
    {
        return $this->hasMany(StudentQuery::class, 'course_id', 'id')->orderBy('id', 'DESC');
    }
}
