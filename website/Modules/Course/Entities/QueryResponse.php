<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\Entities\Notification;
use Modules\User\Entities\Profile;

class QueryResponse extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'query_id',
        'responder_id',
        'body',
        'tagged_query_response_id',
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
                $model->replies()->delete();

                $model->notifications()->delete();
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

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'ref_id', 'id')->where('ref_model_name', 'query_responses')->orderBy('id', 'DESC');
    }

    public function mainQuery()
    {
        return $this->belongsTo(StudentQuery::class, 'query_id', 'id')->with('student');
    }

    public function responder()
    {
        return $this->belongsTo(Profile::class, 'responder_id', 'id');
    }

    public function taggedQueryResponse()
    {
        return $this->belongsTo(QueryResponse::class, 'tagged_response_id', 'id')->with(['responder', 'mainQuery']);
    }

    public function replies()
    {
        return $this->hasMany(QueryResponse::class, 'tagged_response_id', 'id')->with(['responder', 'mainQuery']);
    }

}
