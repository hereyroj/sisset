<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class mandamiento_finalizacion extends Model
{
    use LogsActivity;

    protected $table = 'mandamiento_finalizacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ma_finalizacion_tipo_id',
        'mandamiento_pago_id',
        'fecha_finalizacion',
        'observacion',
        'documento'
    ];

    protected static $logAttributes = [
        'ma_finalizacion_tipo_id',
        'mandamiento_pago_id',
        'fecha_finalizacion',
        'observacion',
        'documento'
    ];

    public function hasMandamientoPago()
    {
        return $this->belongsTo('App\mandamiento_pago', 'mandamiento_pago_id');
    }

    public function hasTipoFinalizacion()
    {
        return $this->belongsTo('App\ma_finalizacion_tipo', 'ma_finalizacion_tipo_id');
    }
}
