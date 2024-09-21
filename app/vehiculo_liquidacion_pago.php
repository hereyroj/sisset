<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_liquidacion_pago extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_liquidacion_pago';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vehiculo_liquidacion_id',
        'numero_consignacion',
        'valor_consignacion',
        'consignacion'
    ];

    protected static $logAttributes = [
        'vehiculo_liquidacion_id',
        'numero_consignacion',
        'valor_consignacion',
        'consignacion'
    ];

    public function hasLiquidacion()
    {
        return $this->belongsTo('App\vehiculo_liquidacion', 'vehiculo_liquidacion_id');
    }
}
