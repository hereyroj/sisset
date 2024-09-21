<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ma_notificacion_entrega extends Model
{
    use LogsActivity;

    protected $table = 'ma_notificacion_entrega';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_entrega',
        'observacion',
        'mandamiento_notificacion_id',
        'documento_entrega'
    ];

    protected static $logAttributes = [
        'fecha_entrega',
        'observacion',
        'mandamiento_notificacion_id',
        'documento_entrega'
    ];

    public function hasMandamientoNotificacion()
    {
        return $this->belongsTo('App\mandamiento_notificacion', 'mandamiento_notificacion_id');
    }
}
