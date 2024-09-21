<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_pqr_envio extends Model
{
    use LogsActivity;

    protected $table = 'gd_pqr_envio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gd_pqr_id','gd_pqr_modalidad_envio_id'
    ];

    protected static $logAttributes = [
        'gd_pqr_id','empresa_mensajeria_id','gd_pqr_modalidad_envio_id','fecha_hora_envio','fecha_hora_entrega','documento_entregado','numero_guia',
    ];

    public function hasEmpresaMensajeria(){
        return $this->belongsTo('App\empresa_mensajeria', 'empresa_mensajeria_id');
    }

    public function hasModalidadEnvio(){
        return $this->belongsTo('App\gd_pqr_modalidad_envio', 'gd_pqr_modalidad_envio_id');
    }
}
