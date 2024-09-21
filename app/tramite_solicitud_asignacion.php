<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_solicitud_asignacion extends Model
{
    use LogsActivity;

    protected $table = 'tramite_solicitud_asignacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tramite_solicitud_id',
        'funcionario_id',
        'tramite_solicitud_turno_id',
        'ventanilla_id'
    ];

    protected static $logAttributes = [
        'tramite_solicitud_id',
        'funcionario_id',
        'tramite_solicitud_turno_id',
        'ventanilla_id'
    ];

    public function hasVentanilla()
    {
        return $this->belongsTo('App\ventanilla', 'ventanilla_id');
    }
}
