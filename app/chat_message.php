<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class chat_message extends Model
{
    use LogsActivity;

    protected $table = 'chat_message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'message',
        'sender_id',
        'reply_id',
        'receiver_id',
        'receiver_type',
        'read_at'
    ];

    protected static $logAttributes = [
        'uuid',
        'message',
        'sender_id',
        'reply_id',
        'receiver_id',
        'receiver_type',
        'read_at'
    ];

    public function hasReceiver()
    {
        return $this->morphTo();
    }

    public function hasSender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }

    public function hasAttachments()
    {
        return $this->hasMany('App\chat_message_attach', 'chat_message_id');
    }
}
