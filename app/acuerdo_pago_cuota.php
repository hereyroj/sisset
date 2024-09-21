<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class acuerdo_pago_cuota extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'acuerdo_pago_cuota';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'valor',
        'fecha_vencimiento',
        'fecha_pago',
        'consignacion_factura',
        'factura_sintrat',
        'vencida',
        'pagada',
        'pendiente',
        'acuerdo_pago_id'
    ];

    protected static $logAttributes = [
        'valor',
        'fecha_vencimiento',
        'fecha_pago',
        'consignacion_factura',
        'factura_sintrat',
        'vencida',
        'pagada',
        'pendiente',
        'acuerdo_pago_id'
    ];

    public function hasAcuerdoPago()
    {
        return $this->belongsTo('App\acuerdo_pago', 'acuerdo_pago_id');
    }

    public function getEstado()
    {
        if($this->fecha_pago != null){
            return 'PAGADA';
        }elseif($this->fecha_vencimiento >= date('Y-m-d')){
            return 'VIGENTE';
        }else{
            return 'VENCIDA';
        }
    }
}
