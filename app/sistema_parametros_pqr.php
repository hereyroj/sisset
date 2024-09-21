<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sistema_parametros_pqr extends Model
{
    use LogsActivity;

    protected $connection = 'mysql_system';

    protected $table = 'parametros_pqr';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'editar_pqr_resuelto',
        'dias_previo_aviso',
        'logo_pqr_radicado',
        'vigencia_id'
    ];

    protected static $logAttributes = [
        'editar_pqr_resuelto',
        'dias_previo_aviso',
        'logo_pqr_radicado',
        'vigencia_id'
    ];

    public function hasVigencia()
    {
        return $this->belongsTo('App\sistema_parametros_vigencia', 'vigencia_id');
    }
}
