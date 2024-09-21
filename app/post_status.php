<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class post_status extends Model
{
    use LogsActivity;
    
    protected $table = 'post_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'show_post'
    ];

    protected static $logAttributes = [
        'name',
        'show_post'
    ];    

    public function hasPosts()
    {
        return $this->hasMany('App\post', 'post_status_id');
    }   
}
