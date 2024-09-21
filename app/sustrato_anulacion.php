<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sustrato_anulacion extends Model
{
    use LogsActivity;

    protected $table = 'sustrato_anulacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sustrato_id',
        'sustrato_anulacion_motivo_id',
        'observacion',
        'funcionario_id'
    ];

    protected static $logAttributes = [
        'sustrato_id',
        'sustrato_anulacion_motivo_id',
        'observacion',
        'funcionario_id'
    ];

    public function hasMotivo()
    {
        return $this->belongsTo('App\sustrato_anulacion_motivo', 'sustrato_anulacion_motivo_id');
    }

    public function hasFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id');
    }
}
