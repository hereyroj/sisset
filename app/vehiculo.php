<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero_motor',
        'numero_chasis',
        'placa',
        'modelo',
        'capacidad_pasajeros',
        'capacidad_toneladas',
        'vehiculo_clase_id',
        'vehiculo_carroceria_id',
        'vehiculo_marca_id',
        'vehiculo_combustible_id',
        'vehiculo_linea_id',
        'cambio_servicio',
        'vehiculo_servicio_id',
        'vehiculo_bateria_tipo_id',
        'bateria_capacidad_watts',
        'color',
        'puertas'
    ];

    protected static $logAttributes = [
        'numero_motor',
        'numero_chasis',
        'placa',
        'modelo',
        'capacidad_pasajeros',
        'capacidad_toneladas',
        'vehiculo_clase_id',
        'vehiculo_carroceria_id',
        'vehiculo_marca_id',
        'vehiculo_combustible_id',
        'vehiculo_linea_id',
        'cambio_servicio',
        'vehiculo_servicio_id',
        'vehiculo_bateria_tipo_id',
        'bateria_capacidad_watts',
        'color',
        'puertas'
    ];

    public function hasTOS()
    {
        return $this->hasMany('App\tarjeta_operacion', 'vehiculo_id')->orderBy('created_at', 'desc');
    }

    public function hasTipoVehiculo()
    {
        return $this->hasOne('App\vehiculo_clase', 'id', 'vehiculo_clase_id');
    }

    public function hasTipoCarroceria()
    {
        return $this->hasOne('App\vehiculo_carroceria', 'id', 'vehiculo_carroceria_id');
    }

    public function hasClaseCombustible()
    {
        return $this->hasOne('App\vehiculo_combustible', 'id', 'vehiculo_combustible_id');
    }

    public function hasNivelServicio()
    {
        return vehiculo_nivel_servicio::find($this->hasEmpresaActiva()->pivot->nivel_servicio_id);
    }

    public function hasMarca()
    {
        return $this->hasOne('App\vehiculo_marca', 'id', 'vehiculo_marca_id');
    }

    public function hasEmpresasTransporte()
    {
        return $this->belongsToMany('App\empresa_transporte', 'vehiculo_empresa_transporte', 'vehiculo_id', 'empresa_transporte_id')->withPivot(['nivel_servicio_id', 'radio_operacion_id', 'zona_operacion', 'numero_interno', 'fecha_afiliacion', 'fecha_retiro', 'estado'])->withTimestamps();
    }

    public function hasEmpresaActiva()
    {
        return $this->hasEmpresasTransporte()->wherePivot('estado', '1')->first();
    }

    public function hasLinea()
    {
        return $this->belongsTo('App\vehiculo_linea', 'vehiculo_linea_id');
    }

    public function hasLiquidaciones()
    {
        return $this->hasMany('App\vehiculo_liquidacion', 'vehiculo_id');
    }

    public function hasPropietarios()
    {
        return $this->belongsToMany('App\vehiculo_propietario', 'vehiculo_has_propietario', 'vehiculo_id', 'vehiculo_propietario_id')->withPivot('estado');
    }

    public function hasVigenciaLiquidada($vigencia)
    {
        return $this->hasLiquidaciones()->whereHas('hasVigencia',function($query) use ($vigencia){
            $query->where('id', $vigencia);
        })->where('anulado',null)->where('vencido',null)->first();
    }

    public function hasPropietariosActivos()
    {
        return $this->hasPropietarios()->wherePivot('estado', 1);
    }
}
