<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function mainQuery()
    {
        return $this->belongsTo(StudentQuery::class, 'query_id', 'id');
    }

    public function responder()
    {
        return $this->belongsTo(Profile::class, 'responder_id', 'id');
    }

    public function taggedQueryResponse()
    {
        return $this->belongsTo(self::class, 'tagged_query_response_id', 'id');
    }
}
