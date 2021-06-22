<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Entities\Profile;

class ChatMedia extends Model
{
    use SoftDeletes;

    protected $table = 'chat_medias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'chat_id',
        'chat_message_id',
        'profile_id',
        'path',
        'thumbnail',
        'ratio',
        'type',
        'size',
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
     * get Profile Chat Member
     *
     * @return void
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    /**
     * get Chat against message
     *
     * @return void
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }

    /**
     * get Chat against message
     *
     * @return void
     */
    public function chatMessage()
    {
        return $this->belongsTo(Chat::class, 'chat_message_id', 'id');
    }
}
