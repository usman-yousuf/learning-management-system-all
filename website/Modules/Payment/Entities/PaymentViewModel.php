<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Entities\Course;
use Modules\User\Entities\Profile;
use Modules\User\Entities\StudentCourse;

class PaymentViewModel extends Model
{
    use HasFactory;

    protected $table = "enrollment_payment_history";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];


    // public function course()
    // {
    //     return $this->belongsTo(Course::class, 'additional_ref_id' , 'id')->where('additional_ref_model_name', 'courses')->orderBy('id', 'DESC')->with('category');
    // }

    // public function freeCourses()
    // {
    //     return $this->belongsTo(Course::class, 'additional_ref_id' , 'id')->where('additional_ref_model_name', 'courses')->where("is_course_free", 1)->orderBy('id', 'DESC')->with('category');
    // }

    // public function paidCourses()
    // {
    //     return $this->belongsTo(Course::class, 'additional_ref_id' , 'id')->where('additional_ref_model_name', 'courses')->where("is_course_free", 0)->orderBy('id', 'DESC')->with('category');
    // }

    // public function enrollment()
    // {
    //     return $this->belongsTo(StudentCourse::class, 'ref_id' ,'id')->orderBy('created_at', 'DESC');
    // }

    // public function payee()
    // {
    //     return $this->hasMany(Profile::class, 'id', 'payee_id')->orderBy('id', 'DESC')->with('user');
    // }

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
