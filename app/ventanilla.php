<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ventanilla extends Model
{
    use LogsActivity;

    protected $table = 'ventanilla';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'codigo',
    ];

    protected static $logAttributes = [
        'name',
        'codigo',
    ];

    public function hasTramitesGruposAsignados()
    {
        return $this->belongsToMany('App\tramite_grupo', 'ventanilla_tramite_grupo', 'ventanilla_id', 'tramite_grupo_id')->withPivot('prioridad');
    }

    public function hasFuncionariosAsignados()
    {
        return $this->belongsToMany('App\User', 'ventanilla_funcionario', 'ventanilla_id', 'funcionario_id')->withPivot('libre', 'fecha_ocupacion', 'fecha_retiro');
    }

    public function hasFuncionarioActivo()
    {
        return $this->hasFuncionariosAsignados()->wherePivot('fecha_ocupacion', date('Y-m-d'))->wherePivot('libre', 'NO')->wherePivot('fecha_retiro', null)->first();
    }

    public function hasFuncionario($id)
    {
        $funcionario = $this->hasFuncionariosAsignados()->where('funcionario_id', $id)->first();
        if ($funcionario != null) {
            return true;
        } else {
            return false;
        }
    }

    public function hasTramiteGrupo($id)
    {
        $grupo = $this->hasTramitesGruposAsignados()->withPivot('prioridad')->where('tramite_grupo_id', $id)->first();
        if ($grupo != null) {
            return $grupo;
        } else {
            return null;
        }
    }
}
