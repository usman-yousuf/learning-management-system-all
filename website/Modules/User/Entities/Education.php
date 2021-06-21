<?php

namespace Modules\User\Entities;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Education extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "educations";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'title',
        'completed_at',
        'university',
        'image',
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
        'completed_at' => 'date'
    ];

    public function getCompletedAtAttribute($value)
    {
        // return $value;
        return date('Y', strtotime($value));
    }

    public function getCertificationImageAttribute()
    {
        return $this->image;
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id')->with('user');
    }
}
