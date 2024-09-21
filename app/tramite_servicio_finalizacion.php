<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class tramite_servicio_finalizacion extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'tramite_servicio_finalizacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tramite_servicio_id',
        'placa_id',
        'sustrato_id',
        'observacion',
        'funcionario_id'        
    ];

    protected static $logAttributes = [
        'tramite_solicitud_id',
        'placa_id',
        'sustrato_id',
        'observacion',
        'funcionario_id'
    ];

    public function hasTramiteServicio()
    {
        return $this->belongsTo('App\tramite_servicio', 'tramite_servicio_id');
    }

    public function hasPlaca()
    {
        return $this->belongsTo('App\placa', 'placa_id');
    }

    public function hasSustrato()
    {
        return $this->belongsTo('App\sustrato', 'sustrato_id');
    }

    public function hasFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id');
    }
}
