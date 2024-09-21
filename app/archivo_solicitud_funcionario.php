<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_solicitud_funcionario extends Model
{
    use LogsActivity;

    protected $table = 'archivo_solicitud_funcionario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'funcionario_id',
        'archivo_sol_mo_id',
        'placa'
    ];

    protected static $logAttributes = [
        'funcionario_id',
        'archivo_sol_mo_id',
        'placa'
    ];

    public function hasSolicitud()
    {
        return $this->morphOne('App\archivo_solicitud', 'origen');
    }

    public function hasMotivo()
    {
        return $this->belongsTo('App\archivo_solicitud_motivo', 'archivo_sol_mo_id');
    }

    public function hasFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id');
    }
}
