<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
Use Modules\Quiz\Entities\Quiz;

class QuizChoice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'quiz_id',
        'body',
        'created_at',
        'updated_at',
    ];

        protected static function boot()
        {
            static::created(function ($model) {
                // Do something after saving
            });
            parent::boot();

            static::updated(function ($model) {
                if($model->deleted_at != null)
                {
                    $model->replies()->delete();
                }
            });
            // delete a query
            static::deleting(function ($model) {
            });
        }


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'id');
    }

}
