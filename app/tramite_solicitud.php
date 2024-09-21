<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_solicitud extends Model
{
    use LogsActivity;

    protected $table = 'tramite_solicitud';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'servicios',
        'observacion',
        'tramite_grupo_id'
    ];

    protected static $logAttributes = [
        'servicios',
        'observacion',
        'tramite_grupo_id'
    ];

    public function hasTramites()
    {
        return $this->belongsToMany('App\tramite', 'tramite_solicitud_has_tramite', 'tramite_solicitud_id', 'tramite_id');
    }

    public function hasTramiteGrupo()
    {
        return $this->belongsTo('App\tramite_grupo', 'tramite_grupo_id');
    }

    public function hasRadicados()
    {
        return $this->hasMany('App\tramite_solicitud_radicado', 'tramite_solicitud_id');
    }

    public function getUltimoRadicado()
    {
        return $this->hasRadicados()->orderBy('created_at', 'desc')->first();
    }

    public function hasTurnos()
    {
        return $this->hasMany('App\tramite_solicitud_turno', 'tramite_solicitud_id');
    }

    public function hasTurnoActivo()
    {
        return $this->hasTurnos()->where('fecha_atencion', null)->where('fecha_anulacion', null)->where('fecha_vencimiento', null)->first();
    }

    public function hasTurnoPendiente()
    {
        return $this->hasTurnos()->where('fecha_llamado', null)->where('fecha_anulacion', null)->where('fecha_vencimiento', null)->first();
    }

    public function hasTurno($id)
    {
        return $this->hasTurnos()->where('id', $id)->first();
    }

    public function hasFuncionariosAsignados()
    {
        return $this->belongsToMany('App\User', 'tramite_solicitud_asignacion', 'tramite_solicitud_id', 'funcionario_id')->withPivot('reasignado', 'motivo_reasignacion', 'fecha_reasignacion', 'tramite_solicitud_turno_id', 'ventanilla_id');
    }

    public function getFuncionarioActivo()
    {
        return $this->hasFuncionariosAsignados()->wherePivot('reasignado', 'NO')->orderBy('tramite_solicitud_asignacion.created_at', 'desc')->first();
    }

    public function canEdit()
    {
        $turnos = $this->hasTurnos();
        if ($turnos->count() > 0 && is_array($turnos)) {
            $turnos->sortByDesc('created_at');
            $ultimo_turno = $turnos->first();
            if ($ultimo_turno->llamado === 'SI') {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function hasAtencion()
    {
        return $this->hasMany('App\tramite_solicitud_atencion', 'tramite_solicitud_id')->orderBy('created_at', 'desc');
    }

    public function getEstadoSolicitud()
    {
        $last = $this->hasAtencion()->first();
        if ($last != null) {
            if ($last->terminacion == 2) {
                return 'pendiente carpeta';
            } elseif ($last->terminacion == 3) {
                return 'pendiente pago';
            } elseif ($last->terminacion == 4) {
                return 'pendiente documentación';
            } elseif ($last->terminacion == 5) {
                return 'anulado';
            }elseif ($last->terminacion == 1) {
                return 'finalizado';
            } else {
                return 'por atender';
            }
        } else {
            return 'por atender';
        }
    }

    public function hasServicios()
    {
        return $this->hasMany('App\tramite_servicio', 'tramite_solicitud_id');
    }

    public function hasUsuario()
    {
        return $this->hasOne('App\tramite_solicitud_usuario', 'tramite_solicitud_id');
    }

    public function hasLicencias()
    {
        return $this->hasMany('App\tramite_licencia', 'tramite_solicitud_id');
    }

    public function hasUltimaAtencion()
    {
        return $this->hasOne('App\tramite_solicitud_atencion', 'tramite_solicitud_id')->latest();
    }
}
