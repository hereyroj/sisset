<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class ma_notificacion_medio extends Model
{
    use LogsActivity;

    protected $table = 'ma_notificacion_medio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero_guia',        
        'empresa_mensajeria_id',
        'mandamiento_notificacion_id',
        'mandamiento_medio_id'
    ];

    protected static $logAttributes = [
        'numero_guia',
        'empresa_mensajeria_id',
        'mandamiento_notificacion_id',
        'mandamiento_medio_id'
    ];

    public function hasMedioNotificacion()
    {
        return $this->belongsTo('App\mandamiento_medio', 'mandamiento_medio_id');
    }

    public function hasEmpresa()
    {
        return $this->belongsTo('App\empresa_mensajeria', 'empresa_mensajeria_id');
    }
}
