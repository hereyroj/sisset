<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_pqr_asignacion extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'gd_pqr_asignacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'funcionario_id',
        'dependencia_id',
        'usuario_asignado_id',
        'gd_pqr_id',
        'estado',
        'descripcion_reasignacion',
        'fecha_reasignacion',
        'responsable',
    ];

    protected static $logAttributes = [
        'funcionario_id',
        'dependencia_id',
        'usuario_asignado_id',
        'gd_pqr_id',
        'estado',
        'descripcion_reasignacion',
        'fecha_reasignacion',
        'responsable',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasPQR()
    {
        return $this->belongsTo('App\gd_pqr', 'gd_pqr_id');
    }

    public function hasUsuarioAsignado()
    {
        return $this->belongsTo('App\User', 'usuario_asignado_id');
    }

    public function hasFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id');
    }

    public function hasDependencia()
    {
        return $this->belongsTo('App\dependencia', 'dependencia_id');
    }    
}
