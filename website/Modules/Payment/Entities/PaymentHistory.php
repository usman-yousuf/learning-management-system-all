<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Entities\Course;
use Modules\User\Entities\Profile;

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

 

    public function course()
    {
        return $this->belongsTo(Course::class, 'ref_id' , 'id')->orderBy('id', 'DESC')->with('category');
    }

    public function freeCourses()
    {
        return $this->belongsTo(Course::class, 'ref_id' , 'id')->where("is_course_free", 1)->orderBy('id', 'DESC')->with('category');
    }

    public function paidCourses()
    {
        return $this->belongsTo(Course::class, 'ref_id' , 'id')->where("is_course_free", 0)->orderBy('id', 'DESC')->with('category');
    }


    // public function additional_ref_id()
    // {
    //     return $this->belongsTo(Course::class, 'additional_ref_id' ,'id')->orderBy('DESC');
    // }

    public function payee()
    {
        return $this->hasMany(Profile::class, 'id', 'payee_id')->orderBy('id', 'DESC')->with('user');
    }

    // public function handouts()
    // {
    //     return $this->hasMany(CourseHandout::class, 'ref_id', 'id')->orderBy('id', 'DESC');
    // }

    // public function outlines()
    // {
    //     return $this->hasMany(CourseOutline::class, 'ref_id', 'id')->orderBy('id', 'DESC');
    // }

    // public function slots()
    // {
    //     return $this->hasMany(CourseSlot::class, 'ref_id', 'id')->orderBy('id', 'DESC');
    // }

    // public function enrolledStudents()
    // {
    //     return $this->hasMany(StudentCourse::class, 'ref_id', 'id')->orderBy('id', 'DESC');
    // }

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class, 'ref_id', 'id')->orderBy('id', 'DESC');
    // }

    // public function queries()
    // {
    //     return $this->hasMany(StudentQuery::class, 'ref_id', 'id')->orderBy('id', 'DESC');
    // }
}
