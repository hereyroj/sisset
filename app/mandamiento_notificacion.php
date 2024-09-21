<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class mandamiento_notificacion extends Model
{
    use LogsActivity;

    protected $table = 'mandamiento_notificacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ma_notificacion_tipo_id',
        'mandamiento_pago_id',
        'consecutivo',
        'documento',
        'fecha_notificacion',
        'fecha_max_presentacion',
        'pantallazo_runt'
    ];

    protected static $logAttributes = [
        'ma_notificacion_tipo_id',
        'mandamiento_pago_id',
        'consecutivo',
        'documento',
        'fecha_notificacion',
        'fecha_max_presentacion',
        'pantallazo_runt'
    ];

    public function hasMandamientoPago()
    {
        return $this->belongsTo('App\mandamiento_pago', 'mandamiento_pago_id');
    }

    public function hasTipoNotificacion()
    {
        return $this->belongsTo('App\ma_notificacion_tipo', 'ma_notificacion_tipo_id');
    }

    public function hasEntrega()
    {
        return $this->hasOne('App\ma_notificacion_entrega', 'mandamiento_notificacion_id');
    }

    public function hasDevolucion()
    {
        return $this->hasOne('App\ma_notificacion_devolucion', 'mandamiento_notificacion_id');
    }

    public function hasMedio()
    {
        return $this->hasOne('App\ma_notificacion_medio', 'mandamiento_notificacion_id');
    }
}
