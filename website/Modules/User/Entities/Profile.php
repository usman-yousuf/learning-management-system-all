<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'gender',
        'user_id',
        'profile_type',
        'profile_image',
        'dob',
        'phone',
        'phone_verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
        'dob' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        // delete a profile
        static::deleting(function ($model) {
            // $model->user()->delete(); // delete user
            // $model->addresses()->delete(); // delete
            // $model->healthMatrix()->delete(); // healthMatrix

            // $model->lifeStyle()->delete(); // lifeStyle
            // $model->insurance()->delete(); // insurance
            // $model->meta()->delete(); //  meta
            // $model->studentCourse()->delete(); // enroled courses
            // $model->reviews()->delete(); // enroled courses

            // $model->ProfileLabTests()->delete(); // ProfileLabTests
            // $model->ProfileCertifications()->delete(); // ProfileCertifications
            // $model->doctorPrescriptions()->delete(); // doctorPrescriptions
            // $model->patientPrescriptions()->delete(); // patientPrescriptions

            // $model->doctorReviews()->delete(); // doctorReviews
            // $model->patientReviews()->delete(); // patientReviews

            // $model->doctorAppointments()->delete(); // doctorAppointments
            // $model->patientAppointments()->delete(); // patientAppointments

            // $model->senderNotifications()->delete(); // senderNotifications
            // $model->receiverNotificatins()->delete(); // receiverNotificatins
            // $model->feedbacks()->delete(); // patientAppointments
        });
    }

    /**
     * get the user info
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'category_id', 'id');
    // }
/**
     * get the lates address info
     */
    public function address()
    {
        return $this->hasOne(Address::class, 'profile_id', 'id')->orderBy('id', 'DESC');
    }

    /**
     * get the all address info against profile
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'profile_id', 'id')->orderBy('id', 'DESC');
    }

    /**
     * get the lates education info
     */
    public function education()
    {
        return $this->hasOne(Education::class, 'profile_id', 'id')->orderBy('id', 'DESC');
    }
    /**
     * get the all education info against profile
     */
    public function educations()
    {
        return $this->hasMany(Education::class, 'profile_id', 'id')->orderBy('id', 'DESC');
    }

    /**
     * get the lates experience info
     */
    public function experience()
    {
        return $this->hasOne(Experience::class, 'profile_id', 'id')->orderBy('id', 'DESC');

    }
    /**
     * get the lates bank info
     */
    public function userBank()
    {
        return $this->hasOne(UserBank::class, 'profile_id', 'id')->orderBy('id', 'DESC');
    }

    /**
     * List all banks aginst a profile
     */
    public function userBanks()
    {
        return $this->hasMany(UserBank::class, 'profile_id', 'id')->orderBy('id', 'DESC');
    }

    public function studentCourses()
    {
        return $this->hasOne(StudentCourse::class, 'student_id', 'id')->orderBy('id', 'DESC');
    }

    // public function lifeStyle()
    // {
    //     return $this->hasOne(LifeStyle::class, 'profile_id', 'id')->orderBy('id', 'DESC');
    // }

    // public function insurance()
    // {
    //     return $this->hasOne(Insurance::class, 'profile_id', 'id')->orderBy('id', 'DESC');
    // }

    /**
     * get the meta info
     */
    public function meta()
    {
        return $this->hasOne(ProfileMeta::class, 'profile_id', 'id')->orderBy('id', 'DESC');
    }

    // public function ProfileLabTests()
    // {
    //     return $this->hasMany(UploadedMedia::class, 'profile_id', 'id')->where('tag', 'LIKE', 'lab_test')->orderBy('id', 'DESC');
    // }

    // public function ProfileCertifications()
    // {
    //     return $this->hasMany(UploadedMedia::class, 'profile_id', 'id')->where('tag', 'LIKE', 'certificate')->orderBy('id', 'DESC');
    // }

    // // doctor prescriptions
    // public function doctorPrescriptions()
    // {
    //     return $this->hasMany(UploadedMedia::class, 'doctor_id', 'id')->where('tag', 'LIKE', 'prescription')->orderBy('id', 'DESC');
    // }

    // patient prescription
    // public function patientPrescriptions()
    // {
    //     return $this->hasMany(UploadedMedia::class, 'profile_id', 'id')->where('tag', 'LIKE', 'prescription')->orderBy('id', 'DESC');
    // }



    // doctor reviws
    // public function doctorReviews()
    // {
    //     return $this->hasMany(Review::class, 'doctor_id', 'id')->orderBy('created_at', 'DESC')->with('patient')->with('appointment');
    // }

    // public function doctorAppointments()
    // {
    //     return $this->hasMany(Appointment::class, 'doctor_id', 'id')->orderBy('created_at', 'DESC');
    // }

    // public function patientAppointments()
    // {
    //     return $this->hasMany(Appointment::class, 'patient_id', 'id')->orderBy('created_at', 'DESC');
    // }

    // public function senderNotifications()
    // {
    //     return $this->hasMany(Notification::class, 'sender_id', 'id')->orderBy('id', 'DESC');
    // }

    // public function receiverNotificatins()
    // {
    //     return $this->hasMany(Notification::class, 'receiver_id', 'id')->orderBy('id', 'DESC');
    // }


    // public function feedbacks()
    // {
    //     return $this->hasMany(Feedback::class, 'sender_id', 'id')->orderBy('id', 'DESC');
    // }

    // patient reviews
    // public function patientReviews()
    // {
    //     return $this->hasMany(Review::class, 'patient_id', 'id')->orderBy('created_at', 'DESC');
    // }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'student_id', 'id')->orderBy('id', 'DESC');
    }
}
