<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class cm_pago extends Model
{
    use LogsActivity;

    protected $table = 'cm_pago';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'valor_intereses',
        'descuento_intereses',
        'numero_factura',
        'numero_consignacion',
        'valor',
        'descuento_valor',
        'cobro_adicional',
        'consignacion',
        'proceso_id',
        'proceso_type',
        'fecha_pago'
    ];

    protected static $logAttributes = [
        'valor_intereses',
        'descuento_intereses',
        'numero_factura',
        'numero_consignacion',
        'valor',
        'descuento_valor',
        'cobro_adicional',
        'consignacion',
        'proceso_id',
        'proceso_type',
        'fecha_pago'
    ];

    public function hasProceso()
    {
        return $this->morphTo('proceso');
    }
}