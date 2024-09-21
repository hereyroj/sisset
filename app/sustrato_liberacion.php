<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sustrato_liberacion extends Model
{
    use LogsActivity;

    protected $table = 'sustrato_liberacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sustrato_id',
        'sus_liberacion_motivo_id',
        'observacion',
        'funcionario_id'
    ];

    protected static $logAttributes = [
        'sustrato_id',
        'sus_liberacion_motivo_id',
        'observacion',
        'funcionario_id'
    ];

    public function hasMotivo()
    {
        return $this->belongsTo('App\sustrato_liberacion_motivo', 'sus_liberacion_motivo_id', 'id');
    }

    public function hasFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id');
    }
}
