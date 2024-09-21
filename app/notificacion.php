<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class notificacion extends Model
{
    use LogsActivity;

    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'notifiable_id',
        'notifiable_type',
        'data',
    ];

    protected static $logAttributes = [
        'type',
        'notifiable_id',
        'notifiable_type',
        'data',
    ];

    public function hasUser()
    {
        return $this->hasOne('App\User', 'id', 'notifiable_id');
    }
}
