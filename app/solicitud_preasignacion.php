<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class solicitud_preasignacion extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'solicitud_preasignacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_documento_solicitante_id',
        'vehiculo_clase_id',
        'vehiculo_servicio_id',
        'numero_documento_solicitante',
        'numero_motor',
        'numero_chasis',
        'correo_electronico_solicitante',
        'numero_telefono_solicitante',
        'nombre_solicitante',
        'nombre_propietario',
        'numero_documento_propietario',
        'cedula_propietario',
        'tipo_documento_propietario_id'
    ];

    protected static $logAttributes = [
        'tipo_documento_solicitante_id',
        'vehiculo_clase_id',
        'vehiculo_servicio_id',
        'numero_documento_identidad',
        'numero_motor',
        'numero_chasis',
        'correo_electronico_solicitante',
        'numero_telefono_solicitante',
        'nombre_solicitante',
        'manifiesto_importacion',
        'factura_compra',
        'nombre_propietario',
        'numero_documento_propietario',
        'cedula_propietario',
        'tipo_documento_propietario_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasPlacas()
    {
        return $this->belongsToMany('App\placa', 'placa_preasignacion', 'solicitud_preasignacion_id', 'placa_id')->withPivot('solicitud_preasignacion_id', 'placa_id', 'fecha_preasignacion', 'fecha_liberacion', 'fecha_matricula')->orderBy('placa_preasignacion.created_at', 'desc')->get();
    }

    public function hasPlacaActiva()
    {
        return $this->hasPlacas()->where('fecha_liberacion', null)->first();
    }

    public function hasVehiculoClase()
    {
        return $this->belongsTo('App\vehiculo_clase', 'vehiculo_clase_id');
    }

    public function hasVehiculoServicio()
    {
        return $this->belongsTo('App\vehiculo_servicio', 'vehiculo_servicio_id');
    }

    public function hasSolicitanteTipoDocumento()
    {
        return $this->belongsTo('App\usuario_tipo_documento', 'tipo_documento_solicitante_id');
    }

    public function hasPropietarioTipoDocumento()
    {
        return $this->belongsTo('App\usuario_tipo_documento', 'tipo_documento_propietario_id');
    }

    public function fueRechazada()
    {
        return $this->belongsToMany('App\solicitud_rechazo_motivo', 'solicitud_preasignacion_rechazo', 'sol_preasignacion_id', 'sol_rechazo_motivo_id')->withPivot('observacion')->withTimestamps()->first();
    }

    public function isTramitable()
    {
        if ($this->hasPlacaActiva() == null && $this->fueRechazada() == null) {
            return true;
        } else {
            return false;
        }
    }

    public function hasRechazo()
    {
        return $this->belongsToMany('App\solicitud_rechazo_motivo', 'solicitud_preasignacion_rechazo', 'sol_preasignacion_id', 'sol_rechazo_motivo_id');
    }

    public function getEstado()
    {
        if($this->hasPlacaActiva() != null){
            if($this->hasPlacaActiva()->pivot->fecha_matricula != null){
                return 'Ha sido matriculada con la placa '.$this->hasPlacaActiva()->name.' el '.$this->hasPlacaActiva()->pivot->fecha_matricula;
            }else{
                return 'Ha sido aprobada y se le ha pre-asignado la placa '.$this->hasPlacaActiva()->name;
            }
        } elseif ($this->fueRechazada() != null) {
            return 'Ha sido rechada por el siguiente motivo: '.$this->fueRechazada()->name. '- Observación: '.$this->fueRechazada()->pivot->observacion;
        } else {
            return 'Está en tramite.';
        }
    }
}
