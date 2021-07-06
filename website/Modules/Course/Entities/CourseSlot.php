<?php

namespace Modules\Course\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Entities\StudentCourse;

class CourseSlot extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'model_start_date', 'model_start_time', 'model_end_date', 'model_end_time', 'time_left'
        , 'model_start_date_php', 'model_start_time_php', 'model_end_date_php', 'model_end_time_php'
        , 'is_lecture_time'
    ];

    protected $withCount = ['enrolments'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'course_id',
        'slot_start',
        'slot_end',
        'day_nums',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'slot_start' => 'datetime',
        'slot_end' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->with('teacher');
    }

    public function enrolments()
    {
        return $this->hasMany(StudentCourse::class, 'slot_id', 'id')->with(['course', 'student', 'slot'])->orderBy('created_at', 'DESC');
    }

    public function lastEnrolment()
    {
        return $this->hasOne(StudentCourse::class, 'slot_id', 'id')
        ->with([
            'course'
            , 'student'
            // , 'slot'
            ])
        ->orderBy('created_at', 'DESC');
    }

    public function getModelStartDateAttribute()
    {
        return date('d M Y', strtotime($this->slot_start));
    }

    public function getisLectureTimeAttribute()
    {
        // $date_now = new \DateTime();
        // $start_date = new \DateTime($this->slot_start);
        // $slot_end = new \DateTime($this->slot_end);

        $date_now = strtotime(date('H:i'));
        $start_date = strtotime(date('H:i', strtotime($this->model_start_time_php)));
        $slot_end = strtotime(date('H:i', strtotime($this->model_end_time_php)));

        if (($date_now >= $start_date) && ($date_now <= $slot_end)) {
            return true;
        }
        return false;
    }

    public function getModelEndDateAttribute()
    {
        return date('d M Y', strtotime($this->slot_end));
    }

    public function getModelStartTimeAttribute()
    {
        return date('h:i A', strtotime($this->slot_start));
    }

    public function getModelEndTimeAttribute()
    {
        return date('h:i A', strtotime($this->slot_end));
    }

    public function getTimeLeftAttribute()
    {
        $time = new DateTime($this->slot_start);
        $diff = $time->diff(new DateTime());
        $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

        return $minutes;
    }

    // formatted in php way
    public function getModelStartDatePhpAttribute()
    {
        return date('Y-m-d', strtotime($this->slot_start));
    }

    public function getModelEndDatePhpAttribute()
    {
        return date('Y-m-d', strtotime($this->slot_end));
    }

    public function getModelStartTimePhpAttribute()
    {
        return date('H:i', strtotime($this->slot_start));
    }

    public function getModelEndTimePhpAttribute()
    {
        return date('H:i', strtotime($this->slot_end));
    }
}
