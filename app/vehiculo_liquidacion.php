<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_liquidacion extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_liquidacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'valor_total',
        'valor_mora_total',
        'valor_descuento_total',
        'fecha_vencimiento',
        'vehiculo_liq_vig_id',
        'vehiculo_id',
        'valor_impuesto',
        'valor_avaluo',
        'codigo',
        'derechos_entidad'
    ];

    protected static $logAttributes = [
        'valor_total',
        'valor_mora_total',
        'valor_descuento_total',
        'fecha_vencimiento',
        'vehiculo_liq_vig_id',
        'vehiculo_id',
        'valor_impuesto',
        'valor_avaluo',
        'codigo',
        'derechos_entidad'
    ];

    public function hasVehiculo()
    {
        return $this->belongsTo('App\vehiculo', 'vehiculo_id');
    }

    public function hasVigencia()
    {
        return $this->belongsTo('App\vehiculo_liquidacion_vigencia', 'vehiculo_liq_vig_id');
    }

    public function hasDescuentos()
    {
        return $this->belongsToMany('App\vehiculo_liquidacion_descuento', 'vehiculo_vig_des', 'vehiculo_liq_id', 'vehiculo_liq_des_id');
    }

    public function hasPago()
    {
        return $this->hasOne('App\vehiculo_liquidacion_pago', 'vehiculo_liquidacion_id');
    }

}
