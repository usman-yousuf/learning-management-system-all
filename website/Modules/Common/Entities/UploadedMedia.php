<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Entities\Profile;

class UploadedMedia extends Model
{
    use SoftDeletes;

    protected $table = 'uploaded_medias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id ',
        'title',
        'path',
        'thumbnail',
        'ratio',
        'type', // media type
        'tag', //prescription, lab_test, etc
        'model_id',
        'model_name',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * get Profile Relationship
     *
     * @return void
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }
}
