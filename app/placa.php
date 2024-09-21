<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class placa extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'placa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'vehiculo_servicio_id',
    ];

    protected static $logAttributes = [
        'name',
        'vehiculo_servicio_id',
    ];

    public function hasPreAsignacionActiva()
    {
        $asignaciones = $this->belongsToMany('App\solicitud_preasignacion', 'placa_preasignacion')->withPivot('solicitud_preasignacion_id', 'placa_id', 'fecha_preasignacion', 'fecha_liberacion', 'fecha_matricula')->withTimestamps()->orderBy('placa_preasignacion.created_at', 'desc');
        if ($asignaciones->count() > 0) {
            return $asignaciones->wherePivot('fecha_preasignacion', '!=', null)->wherePivot('fecha_matricula', null)->wherePivot('fecha_liberacion', null)->first();
        } else {
            return null;
        }
    }

    public function estaMatriculado()
    {
        $asignaciones = $this->belongsToMany('App\solicitud_preasignacion', 'placa_preasignacion')->withPivot('solicitud_preasignacion_id', 'placa_id', 'fecha_preasignacion', 'fecha_liberacion', 'fecha_matricula')->withTimestamps()->orderBy('placa_preasignacion.created_at', 'desc');
        if ($asignaciones->count() > 0) {
            return $asignaciones->wherePivot('fecha_matricula', '!=', null)->first();
        } else {
            return null;
        }
    }

    public function isAvailable()
    {
        $solicitudes = $this->belongsToMany('App\solicitud_preasignacion', 'placa_preasignacion')->withPivot('solicitud_preasignacion_id', 'placa_id', 'fecha_preasignacion', 'fecha_liberacion', 'fecha_matricula')->withTimestamps()->orderBy('placa_preasignacion.created_at', 'desc')->get();
        if ($solicitudes->count() > 0) {
            $solicitud = $solicitudes->last();
            if ($solicitud->pivot->fecha_liberacion != null && $solicitud->pivot->fecha_matricula == null) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function hasSolicitudesPreAsignaciones()
    {
        return $this->belongsToMany('App\solicitud_preasignacion', 'placa_preasignacion');
    }

    public function hasVehiculosClases()
    {
        return $this->belongsToMany('App\vehiculo_clase', 'placa_vehiculo_clase', 'placa_id', 'vehiculo_clase_id')->withPivot('vehiculo_servicio_id')->withTimestamps();
    }

    public function hasVehiculoClase($id)
    {
        $clase = $this->hasVehiculosClases()->where('id', $id)->first();
        if($clase != null){
            return true;
        }else{
            return false;
        }
    }

    public function hasVehiculoServicio()
    {
        return $this->belongsTo('App\vehiculo_servicio', 'vehiculo_servicio_id');
    }

    public function hasConsumo()
    {
        return $this->hasOne('App\tramite_servicio_finalizacion', 'placa_id');
    }
}
