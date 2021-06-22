<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Common\Entities\Notification;
use Modules\User\Entities\Profile;

class Chat extends Model
{
    use SoftDeletes;

    protected $table = 'chats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'title',
        'type',
        'status',
        'parent_id',
        'last_message_id',
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
     * Anonymous scope
     */
    protected static function boot()
    {
        parent::boot();

        // delete an appointment
        static::deleting(function ($model) {
            $model->members()->delete(); // members
            $model->mesages()->delete(); // mesages
            $model->notifications()->delete(); //notifications
        });
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'type_id', 'id')->where('noti_model', 'chats')->orderBy('id', 'DESC');
    }

    /**
     * get Profile of person who initiated Chat
     *
     * @return void
     */
    public function parent()
    {
        return $this->belongsTo(Profile::class, 'parent_id', 'id');
    }

    /**
     * get Last Message of Chat
     *
     * @return void
     */
    public function lastMessage()
    {
        return $this->belongsTo(ChatMessage::class, 'last_message_id', 'id');
    }

    /**
     * get Chat Members
     *
     * @return void
     */
    public function members()
    {
        return $this->hasMany(ChatMember::class, 'chat_id', 'id')->orderBy('id', 'DESC');
    }

    /**
     * get Chat Members
     *
     * @return void
     */
    public function mesages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id', 'id')->orderBy('id', 'DESC');
    }
}
