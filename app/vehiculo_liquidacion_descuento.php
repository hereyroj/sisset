<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_liquidacion_descuento extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_liquidacion_descuento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'concepto',
        'porcentaje',
        'vigente_desde',
        'vigente_hasta',
        've_li_vi_id'
    ];

    protected static $logAttributes = [
        'concepto',
        'porcentaje',
        'vigente_desde',
        'vigente_hasta',
        've_li_vi_id'
    ];

    public function hasLiquidaciones()
    {
        return $this->belongsToMany('App\vehiculo_liquidacion', 'vehiculo_liq_des', 'vehiculo_liq_des_id', 'vehiculo_liq_id');
    }

    public function hasVigencia()
    {
        return $this->belongsTo('App\vehiculo_liquidacion_vigencia', 've_li_vi_id');
    }
}
