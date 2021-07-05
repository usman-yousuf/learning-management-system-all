<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Course\Entities\Course;
// use Modules\Chat\Entities\Chat;
use Modules\User\Entities\Profile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Assignment\Entities\Assignment;
use Modules\Quiz\Entities\Quiz;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_read',
    ];

    protected $with = ['sender'];

    public function sender()
    {
        return $this->belongsTo(Profile::class, 'sender_id', 'id')->with('user');
    }

    public function receiver()
    {
        return $this->belongsTo(Profile::class, 'receiver_id', 'id')->with('user');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'ref_id', 'id')->with('category');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'ref_id', 'id')->with(['course', 'slot']);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'ref_id', 'id')->with(['course', 'slot']);
    }


    // public function review()
    // {
    //     return  $this->belongsTo(Review::class, 'type_id', 'id');
    // }

}
