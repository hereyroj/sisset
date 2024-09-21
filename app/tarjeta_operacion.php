<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class tarjeta_operacion extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'tarjeta_operacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sede',
        'numero_interno',
        'fecha_vencimiento',
        'placa',
        'zona_operacion',
        'tipo_vehiculo_id',
        'tipo_carroceria_id',
        'nivel_servicio_id',
        'marca_vehiculo_id',
        'radio_operacion_id',
        'numero_motor',
        'empresa_transporte_id',
        'clase_combustible_id',
    ];

    protected static $logAttributes = [
        'id',
        'sede',
        'numero_interno',
        'fecha_vencimiento',
        'placa',
        'zona_operacion',
        'tipo_vehiculo_id',
        'tipo_carroceria_id',
        'nivel_servicio_id',
        'marca_vehiculo_id',
        'radio_operacion_id',
        'numero_motor',
        'empresa_transporte_id',
        'clase_combustible_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasTipoVehiculo()
    {
        return $this->hasOne('App\vehiculo_clase', 'id', 'tipo_vehiculo_id');
    }

    public function hasTipoCarroceria()
    {
        return $this->hasOne('App\vehiculo_carroceria', 'id', 'tipo_carroceria_id');
    }

    public function hasEmpresaTransporte()
    {
        return $this->hasOne('App\empresa_transporte', 'id', 'empresa_transporte_id');
    }

    public function hasClaseCombustible()
    {
        return $this->hasOne('App\vehiculo_combustible', 'id', 'clase_combustible_id');
    }

    public function hasMarca()
    {
        return $this->hasOne('App\vehiculo_marca', 'id', 'marca_vehiculo_id');
    }

    public function hasNivelServicio()
    {
        return $this->hasOne('App\vehiculo_nivel_servicio', 'id', 'nivel_servicio_id');
    }

    public function hasRadioOperacion()
    {
        return $this->hasOne('App\vehiculo_radio_operacion', 'id', 'radio_operacion_id');
    }

    public function hasManyFileHistory()
    {
        return $this->hasMany('App\to_file_history');
    }
}