<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_solicitud_atencion extends Model
{
    use LogsActivity;

    protected $table = 'tramite_solicitud_atencion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tramite_solicitud_id',
        'tramite_solicitud_turno_id',
        'ventanilla_id',
        'observacion',
        'terminacion',
        'funcionario_id'
    ];

    protected static $logAttributes = [
        'tramite_solicitud_id',
        'tramite_solicitud_turno_id',
        'ventanilla_id',
        'observacion',
        'terminacion',
        'funcionario_id'
    ];

    public function hasFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id');
    }
}
