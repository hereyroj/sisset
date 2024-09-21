<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_solicitud_turno extends Model
{
    use LogsActivity;

    protected $table = 'tramite_solicitud_turno';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'turno',
        'tramite_solicitud_id',
        'tramite_solicitud_origen_id',
        'preferente'
    ];

    protected static $logAttributes = [
        'turno',
        'tramite_solicitud_id',
        'tramite_solicitud_origen_id',
        'fecha_llamado',
        'fecha_atencion',
        'fecha_vencimiento',
        'fecha_anulacion',
        'preferente',
        'fecha_rellamado',
        'funcionario_rellamado_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'fecha_llamado',
        'fecha_atencion',
        'fecha_vencimiento',
        'fecha_anulacion',
    ];

    public function hasSolicitud()
    {
        return $this->belongsTo('App\tramite_solicitud', 'tramite_solicitud_id');
    }

    public function hasOrigen()
    {
        return $this->belongsTo('App\tramite_solicitud_origen', 'tramite_solicitud_origen_id');
    }

    public function hasAsignaciones()
    {
        return $this->hasMany('App\tramite_solicitud_asignacion', 'tramite_solicitud_turno_id')->orderBy('created_at','desc');
    }

    public function hasAsignacion()
    {
        /*$asignacion = \DB::table('tramite_solicitud_asignacion')->select('*')->where('tramite_solicitud_turno_id', $this->id)->orderBy('created_at', 'desc')->first();
        if ($asignacion != null) {
            return $asignacion;
        } else {
            return null;
        }*/
        return $this->hasAsignaciones()->first();
    }

    public function asignarTurno($ventanilla_id)
    {
        try {
            \DB::table('tramite_solicitud_asignacion')->insert([
                'funcionario_id' => auth()->user()->id,
                'tramite_solicitud_id' => $this->hasSolicitud->id,
                'tramite_solicitud_turno_id' => $this->id,
                'ventanilla_id' => $ventanilla_id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function hasUsuarioSolicitante()
    {
        return $this->hasOne('App\tramite_solicitud_usuario', 'tramite_solicitud_turno_id');
    }

    public function hasFuncionarioReLlamado()
    {
        return $this->belongsTo('App\User', 'funcionario_rellamado_id');
    }

    public function hasAtencion()
    {
        return $this->hasOne('App\tramite_solicitud_atencion', 'tramite_solicitud_turno_id');
    }
}
