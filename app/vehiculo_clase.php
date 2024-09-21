<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_clase extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'vehiculo_clase';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'required_letter',
        'pre_asignable'
    ];

    protected static $logAttributes = [
        'name',
        'required_letter',
        'pre_asignable'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasLetrasTerminacion()
    {
        return $this->belongsToMany('App\vehiculo_clase_letra_terminacion', 'vehiculo_clase_has_lt', 'vehiculo_clase_id', 'letra_terminacion_id');
    }

    public function checkHasLT($lt_id)
    {
        $lst = $this->hasLetrasTerminacion()->where('letra_terminacion_id', '=', $lt_id)->count();
        if ($lst > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenerLetras()
    {
        return $this->belongsToMany('App\vehiculo_clase_letra_terminacion', 'vehiculo_clase_has_lt', 'vehiculo_clase_id', 'letra_terminacion_id');
    }

    public function hasCarpetas()
    {
        return $this->hasMany('App\archivo_carpeta', 'vehiculo_clase_id', 'id');
    }

    public function hasServicios()
    {
        return $this->belongsToMany('App\vehiculo_servicio', 'vehiculo_clase_has_servicio', 'vehiculo_clase_id', 'vehiculo_servicio_id');
    }

    public function hasVehiculos()
    {
        return $this->hasMany('App\Vehiculo','vehiculo_clase_id','id');
    }

    public function hasPreAsignaciones()
    {
        return $this->hasMany('App\solicitud_preasignacion','vehiculo_clase_id');
    }

    public function hasMarcas()
    {
        return $this->belongsToMany('App\vehiculo_marca', 'vehiculo_marca_has_clase', 'vehiculo_clase_id', 'vehiculo_marca_id');
    }
}
