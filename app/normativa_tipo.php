<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class normativa_tipo extends Model
{
    use LogsActivity;

    protected $table = 'normativa_tipo';

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

    public function hasNormativas()
    {
        return $this->hasMany('App\normativa', 'normativa_tipo_id');
    }
}
