<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

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
        return $this->hasMany(CourseSlot::class, 'course_id', 'id')->orderBy('id', 'DESC');
    }
}
