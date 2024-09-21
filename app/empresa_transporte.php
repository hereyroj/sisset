<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class empresa_transporte extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'empresa_transporte';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected static $logAttributes = [
        'name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasManyTOS()
    {
        return $this->hasMany('App\tarjeta_operacion', 'empresa_transporte_id', 'id');
    }

    public function hasVehiculosAfiliados()
    {
        return $this->belongsToMany('App\vehiculo', 'vehiculo_empresa_transporte', 'empresa_transporte_id', 'vehiculo_id')->withPivot(['nivel_servicio_id', 'radio_operacion_id', 'zona_operacion', 'numero_interno', 'fecha_afiliacion', 'fecha_retiro', 'estado'])->withTimestamps();
    }
}
