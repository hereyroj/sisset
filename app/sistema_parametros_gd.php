<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sistema_parametros_gd extends Model
{
    use LogsActivity;

    protected $connection = 'mysql_system';

    protected $table = 'parametros_gestion_documental';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'radicado_entrada_consecutivo',
        'radicado_salida_consecutivo',
        'sancion_consecutivo',
        'vigencia_id',
        'encabezado_documento',
        'pie_documento'
    ];

    protected static $logAttributes = [
        'radicado_entrada_consecutivo',
        'radicado_salida_consecutivo',
        'sancion_consecutivo',
        'vigencia_id',
        'encabezado_documento',
        'pie_documento'
    ];

    public function hasVigencia()
    {
        return $this->belongsTo('App\sistema_parametros_vigencia', 'vigencia_id');
    }
}
