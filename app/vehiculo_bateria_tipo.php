<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_bateria_tipo extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_bateria_tipo';

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
}
