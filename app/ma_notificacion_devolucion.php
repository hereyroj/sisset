<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ma_notificacion_devolucion extends Model
{
    use LogsActivity;

    protected $table = 'ma_notificacion_devolucion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_devolucion',
        'observacion',
        'mandamiento_notificacion_id',
        'ma_devolucion_motivo_id'
    ];

    protected static $logAttributes = [
        'fecha_devolucion',
        'observacion',
        'mandamiento_notificacion_id',
        'ma_devolucion_motivo_id'
    ];

    public function hasMotivo()
    {
        return $this->belongsTo('App\ma_devolucion_motivo', 'ma_devolucion_motivo_id');
    }

    public function hasMandamientoNotificacion()
    {
        return $this->belongsTo('App\mandamiento_notificacion', 'mandamiento_notificacion_id');
    }
}
