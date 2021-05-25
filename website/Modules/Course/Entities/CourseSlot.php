<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSlot extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['model_start_date', 'model_start_time', 'model_end_date', 'model_end_time'];

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
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function getModelStartDateAttribute()
    {
        return date('d M Y', strtotime($this->slot_start));
    }

    public function getModelEndDateAttribute()
    {
        return date('d M Y', strtotime($this->slot_end));
    }

    public function getModelStartTimeAttribute()
    {
        return date('H:i A', strtotime($this->slot_start));
    }

    public function getModelEndTimeAttribute()
    {
        return date('H:i A', strtotime($this->slot_end));
    }
}
