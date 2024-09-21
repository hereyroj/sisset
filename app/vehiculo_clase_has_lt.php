<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_clase_has_lt extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'vehiculo_clase_has_lt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vehiculo_clase_id',
        'letra_terminacion_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected static $logAttributes = [
        'vehiculo_clase_id',
        'letra_terminacion_id',
    ];
}
