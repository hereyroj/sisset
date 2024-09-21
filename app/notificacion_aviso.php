<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class notificacion_aviso extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'notificacion_aviso';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_publicacion',
        'fecha_desfijacion',
        'numero_documento',
        'numero_proceso',
        'nombre_notificado',
        'documento_notificacion',
        'not_aviso_tipo_id'
    ];

    protected static $logAttributes = [
        'fecha_publicacion',
        'fecha_desfijacion',
        'numero_documento',
        'numero_proceso',
        'nombre_notificado',
        'documento_notificacion',
        'not_aviso_tipo_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasTipoNotificacion()
    {
        return $this->belongsTo('App\notificacion_aviso_tipo', 'not_aviso_tipo_id');
    }
}