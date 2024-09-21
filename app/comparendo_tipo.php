<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class comparendo_tipo extends Model
{
    use LogsActivity;

    protected $table = 'comparendo_tipo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    protected static $logAttributes = [
        'name'
    ];

    public function hasComparendos()
    {
        return $this->hasMany('App\comparendo', 'comparendo_tipo_id');
    }
}
