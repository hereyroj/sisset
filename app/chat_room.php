<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class chat_room extends Model
{
    use LogsActivity;

    protected $table = 'chat_room';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'password',
        'name',
        'description',
        'logo'
    ];

    protected static $logAttributes = [
        'uuid',
        'password',
        'name',
        'description',
        'logo'
    ];

    public function hasUsers()
    {
        return $this->belongsToMany('App\User', 'chat_room_id', 'user_id')->withPivot('leave','leave_at','admin');
    }

    public function hasActiveUsers()
    {
        return $this->hasUsers()->wherePivot('leave', false)->wherePivot('leavet_at', null);
    }

    public function hasMessages()
    {
        return $this->morphMany('App\chat_message', 'receiver');
    }

    public function leaveUser($id)
    {
        $user = $this->hasUsers()->where('id', $id)->first();
        $user->pivot->leave = true;
        $user->pivot->leave_at = date('Y-m-d H:i:s');
        return $user->pivot->save();
    }

    public function getLastMessage()
    {
        return $this->hasMessages()->orderBy('created_at', 'desc')->first();
    }
}
