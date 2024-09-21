<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_clase_letra_terminacion extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'vehiculo_clase_letra_terminacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected static $logAttributes = [
        'name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function couldHaveClaseVehiculo()
    {
        return $this->belongsToMany('App\vehiculo_clase', 'vehiculo_clase_has_lt', 'vehiculo_clase_id', 'letra_terminacion_id', 'letra_terminacion_id');
    }
}
