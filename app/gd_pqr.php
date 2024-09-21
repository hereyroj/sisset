<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_pqr extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'gd_pqr';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asunto',
        'gd_pqr_clase_id',
        'anexos',
        'limite_respuesta',
        'previo_aviso',
        'numero_oficio',
        'responde_oficio',
        'descripcion',
        'gd_medio_traslado_id',
        'tipo_pqr',
        'pdf',
        'uuid',
        'radicados_respuesta'
    ];

    protected static $logAttributes = [
        'asunto',
        'gd_pqr_clase_id',
        'anexos',
        'limite_respuesta',
        'previo_aviso',
        'numero_oficio',
        'responde_oficio',
        'descripcion',
        'gd_medio_traslado_id',
        'tipo_pqr',
        'pdf',
        'documento_radicado',
        'uuid',
        'radicados_respuesta'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function getRadicadoEntrada()
    {
        return $this->morphOne('App\gd_radicado_entrada', 'origen');
    }

    public function getRadicadoSalida()
    {
        return $this->morphOne('App\gd_radicado_salida', 'origen');
    }

    public function hasClase()
    {
        return $this->belongsTo('App\gd_pqr_clase', 'gd_pqr_clase_id');
    }

    public function hasClasificacion()
    {
        return $this->hasOne('App\gd_pqr_clasificacion', 'gd_pqr_id');
    }

    public function hasAsignaciones()
    {
        return $this->hasMany('App\gd_pqr_asignacion', 'gd_pqr_id', 'id')->with('hasUsuarioAsignado', 'hasFuncionario', 'hasDependencia')->orderBy('created_at', 'desc');
    }

    public function getAsignacionesActivas()
    {
        $asignaciones = gd_pqr_asignacion::where('gd_pqr_id', $this->id)->get();
        if ($asignaciones->count() > 0) {
            $asignaciones_activas = $asignaciones->filter(function ($item) {
                return $item->estado == 1;
            });

            return $asignaciones_activas;
        } else {
            return null;
        }
    }

    public function hasRespuestas()
    {
        return $this->belongsToMany('App\gd_pqr', 'gd_pqr_respuesta', 'gd_pqr_respondido_id','gd_pqr_respuesta_id')->orderBy('created_at','asc');
    }

    public function getMedioTraslado()
    {
        return $this->belongsTo('App\gd_medio_traslado', 'gd_medio_traslado_id');
    }

    public function hasPeticionario()
    {
        return $this->hasOne('App\gd_pqr_peticionario', 'gd_pqr_id');
    }

    public function hasEnvio()
    {
        return $this->hasOne('App\gd_pqr_envio', 'gd_pqr_id');
    }

    public function diasPasados()
    {
        if ($this->hasClase->required_answer == 'SI'  && $this->limite_respuesta != null) {
            if ($this->hasRespuestas->count() > 0) {
                $primerRespuesta = $this->hasRespuestas->first();
                if ($this->limite_respuesta >= $primerRespuesta->created_at->format('Y-m-d')) {
                    return null;
                } else {
                    if ($this->hasClase->dia_clase == 'HABIL') {
                        return calendario::whereBetween('fecha', [
                            Carbon::createFromFormat('Y-m-d', $this->limite_respuesta)->addDay(1)->format('Y-m-d'),
                            $primerRespuesta->created_at->format('Y-m-d'),
                        ])->where('laboral', '1')->count();
                    } else {
                        return calendario::whereBetween('fecha', [
                            Carbon::createFromFormat('Y-m-d', $this->limite_respuesta)->addDay(1)->format('Y-m-d'),
                            $primerRespuesta->created_at->format('Y-m-d'),
                        ])->count();
                    }
                }
            } else {
                if ($this->limite_respuesta >= date('Y-m-d')) {
                    return null;
                } else {
                    if ($this->hasClase->dia_clase == 'HABIL') {
                        return calendario::whereBetween('fecha', [
                            Carbon::createFromFormat('Y-m-d', $this->limite_respuesta)->addDay(1)->format('Y-m-d'),
                            date('Y-m-d'),
                        ])->where('laboral', '1')->count();
                    } else {
                        return calendario::whereBetween('fecha', [Carbon::createFromFormat('Y-m-d', $this->limite_respuesta)->addDay(1)->format('Y-m-d'), date('Y-m-d')])->count();
                    }
                }
            }
        } else {
            return null;
        }
    }

    public function diasRestantes()
    {
        if($this->hasClase->required_answer == 'SI' && $this->limite_respuesta != null){
            if ($this->hasRespuestas->count() <= 0) {
                return calendario::whereBetween('fecha', [
                    \Carbon\Carbon::now()->format('Y-m-d'),
                    $this->limite_respuesta,
                ])->count();
            } else {
                return null;
            }
        }else{
            return null;
        }
    }

    public function comprobarUsuarioAsignacion($id)
    {
        if ($this->hasAsignaciones()->where('estado', 1)->where('usuario_asignado_id', $id)->first() != null) {
            return true;
        } elseif(
            //Funcion para validar el acceso a los documentos radicados de los procesos CoSa cuando no es el que radica o es el responsable
            $this->getRespondidos()->whereHas('hasAsignaciones', function ($query) use ($id) {
                    $query->where('usuario_asignado_id', $id)->where('estado', 1);
            })->first() != null){
            return true;
        }else{
            return false;
        }
    }

    public function comprobarUsuarioResponsable($id)
    {
        $asignacion = $this->hasAsignaciones()->where('estado', 1)->where('responsable', 1)->where('usuario_asignado_id', $id)->first();
        if ($asignacion != null) {
            return true;
        } else {
            return false;
        }
    }

    public function comprobarFuncionarioPeticionario($user_id)
    {
        if($this->hasPeticionario->funcionario_id == $user_id){
            return true;
        }else{
            return false;
        }
    }

    public function hasEntrega()
    {
        return $this->hasOne('App\gd_pqr_entrega', 'gd_pqr_id');
    }

    public function getRespondidos()
    {
        return $this->belongsToMany('App\gd_pqr', 'gd_pqr_respuesta', 'gd_pqr_respuesta_id','gd_pqr_respondido_id');
    }

    public function hasAnulacion()
    {
        return $this->hasOne('App\gd_pqr_anulacion', 'gd_pqr_id');
    }

    public function hasResponsable()
    {
        return $this->hasAsignaciones()->where('estado', 1)->where('responsable', 1)->first();
    }
}
