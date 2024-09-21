<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class acuerdo_pago_deudor extends Model
{
    use LogsActivity;

    protected $table = 'acuerdo_pago_deudor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'tipo_documento_id',
        'numero_documento',
        'telefono',
        'correo_electronico',
        'direccion',
        'acuerdo_pago_id'
    ];

    protected static $logAttributes = [
        'nombre',
        'tipo_documento_id',
        'numero_documento',
        'telefono',
        'correo_electronico',
        'direccion',
        'acuerdo_pago_id'
    ];

    public function hasAcuerdoPago()
    {
        return $this->belongsTo('App\acuerdo_pago', 'acuerdo_pago_id');
    }

    public function hasTipoDocumento()
    {
        return $this->belongsTo('App\usuario_tipo_documento', 'tipo_documento_id');
    }
}
