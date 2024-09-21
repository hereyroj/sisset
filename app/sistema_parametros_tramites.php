<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sistema_parametros_tramites extends Model
{
    use LogsActivity;

    protected $connection = 'mysql_system';

    protected $table = 'parametros_tramites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inicio_atencion',
        'fin_atencion',
        'turno_rellamado',
        'turno_preferencial',
        'turno_transferencia',
        'turno_tiempo_espera',
        'vigencia_id'
    ];

    protected static $logAttributes = [
        'inicio_atencion',
        'fin_atencion',
        'turno_rellamado',
        'turno_preferencial',
        'turno_transferencia',
        'turno_logo',
        'turno_tiempo_espera',
        'vigencia_id'
    ];

    public function hasVigencia()
    {
        return $this->belongsTo('App\sistema_parametros_vigencia', 'vigencia_id');
    }
}
