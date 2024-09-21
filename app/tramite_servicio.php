<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_servicio extends Model
{
    use LogsActivity;

    protected $table = 'tramite_servicio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tramite_solicitud_id',
        'vehiculo_servicio_id',
        'vehiculo_clase_id',
        'placa',
        'documento_propietario',
        'funcionario_id'
    ];

    protected static $logAttributes = [
        'tramite_solicitud_id',
        'vehiculo_servicio_id',
        'vehiculo_clase_id',
        'placa',
        'documento_propietario',
        'funcionario_id'
    ];

    public function hasSolicitud()
    {
        return $this->belongsTo('App\tramite_solicitud', 'tramite_solicitud_id');
    }

    public function hasTramiteSolicitud()
    {
        return $this->belongsTo('App\tramite_solicitud', 'tramite_solicitud_id');
    }

    public function hasRecibos()
    {
        return $this->hasMany('App\tramite_servicio_recibo', 'tramite_servicio_id');
    }

    public function hasFinalizacion()
    {
        return $this->hasOne('App\tramite_servicio_finalizacion', 'tramite_servicio_id');
    }

    public function hasEstados()
    {
        return $this->belongsToMany('App\tramite_servicio_estado', 'tramite_servicio_has_estado', 'tramite_servicio_id', 'tramite_servicio_estado_id')->withPivot(['funcionario_id', 'observacion'])->withTimeStamps();
    }

    public function hasEstado($estado_id)
    {
        return $this->hasEstados()->where('tramite_servicio_estado_id', $estado_id)->first();
    }

    public function hasSolicitudesCarpeta()
    {
        return $this->morphMany('App\archivo_solicitud', 'origen')->orderBy('created_at');
    }

    public function getUltimoEstadoAsignado()
    {
        return $this->hasEstados()->orderBy('tramite_servicio_has_estado.created_at', 'desc')->first();
    }

    public function hasSolicitudCarpetaPendiente()
    {
        return $this->hasSolicitudesCarpeta()->where('archivo_carpeta_prestamo_id', null)->first();
    }

    public function hasVehiculoClase()
    {
        return $this->belongsTo('App\vehiculo_clase', 'vehiculo_clase_id');
    }

    public function hasVehiculoServicio()
    {
        return $this->belongsTo('App\vehiculo_servicio', 'vehiculo_servicio_id');
    }

    public function hasTramites()
    {
        return $this->belongsToMany('App\tramite', 'tramite_servicio_has_tramite', 'tramite_servicio_id', 'tramite_id');
    }

    public function hasFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id');
    }
}
