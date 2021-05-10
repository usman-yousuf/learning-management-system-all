<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Entities\Profile;

class ChatMessage extends Model
{
    use SoftDeletes;

    protected $table = 'chat_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'chat_id',
        'tagged_message_id',
        'message',
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
     * get Profile of person who initiated Chat
     *
     * @return void
     */
    public function sender()
    {
        return $this->belongsTo(Profile::class, 'sender_id', 'id');
    }

    /**
     * get Chat against message
     *
     * @return void
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id')->with('appointment');
    }

    /**
     * get Tagged Message against current message
     *
     * @return void
     */
    public function taggedMessage()
    {
        return $this->belongsTo(self::class, 'tagged_message_id', 'id');
    }
}
