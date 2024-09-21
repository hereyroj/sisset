<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class comparendo_inmovilizacion extends Model
{
    use LogsActivity;

    protected $table = 'comparendo_inmovilizacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comparendo_id',
        'inmovilizacion_tipo_id',
        'observacion',
        'patio_nombre',
        'patio_direccion',
        'grua_numero',
        'grua_placa',
        'consecutivo'    
    ];

    protected static $logAttributes = [
        'comparendo_id',
        'inmovilizacion_tipo_id',
        'observacion',
        'patio_nombre',
        'patio_direccion',
        'grua_numero',
        'grua_placa',
        'consecutivo'    
    ];

    public function hasComparendo()
    {
        return $this->belongsTo('App\comparendo', 'comparendo_id');
    }

    public function hasTipoInmovilizacion()
    {
        return $this->belongsTo('App\comparendo_inmovilizacion_tipo', 'inmovilizacion_tipo_id');
    }
}
