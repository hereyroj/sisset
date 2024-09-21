<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class comparendo_vehiculo extends Model
{
    use LogsActivity;

    protected $table = 'comparendo_vehiculo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'placa',
        'licencia_transito',
        'propietario_nombre',
        'vehiculo_servicio_id',
        'vehiculo_clase_id',
        'tarjeta_operacion',
        'vehiculo_radio_operacion_id',
        'empresa_transportadora_id',
        'comparendo_id',
        'licencia_transito_otto',
        'vehiculo_nivel_servicio_id',
        'prop_tipo_documento_id',    
        'prop_numero_documento'    
    ];

    protected static $logAttributes = [
        'placa',
        'licencia_transito',
        'propietario_nombre',
        'vehiculo_servicio_id',
        'vehiculo_clase_id',
        'tarjeta_operacion',
        'vehiculo_radio_operacion_id',
        'empresa_transportadora_id',
        'comparendo_id',
        'licencia_transito_otto',
        'vehiculo_nivel_servicio_id',
        'prop_tipo_documento_id',
        'prop_numero_documento'  
    ];

    public function hasVehiculoClase()
    {
        return $this->hasOne('App\vehiculo_clase', 'id', 'vehiculo_clase_id');
    }

    public function hasEmpresaTransporte()
    {
        return $this->belongsTo('App\empresa_transporte', 'empresa_transportadora_id');
    }

    public function hasVehiculoRadioOperacion()
    {
        return $this->belongsTo('App\vehiculo_radio_operacion', 'vehiculo_radio_operacion_id');
    }

    public function hasVehiculoServicio()
    {
        return $this->belongsTo('App\vehiculo_servicio', 'vehiculo_servicio_id');
    }

    public function hasComparendo()
    {
        return $this->belongsTo('App\comparendo', 'comparendo_id');
    }

    public function hasTipoDocumentoPropietario()
    {
        return $this->belongsTo('App\usuario_tipo_documento', 'prop_tipo_documento_id');
    }

    public function hasVehiculoNivelServicio()
    {
        return $this->belongsTo('App\vehiculo_nivel_servicio', 'vehiculo_nivel_servicio_id');
    }
}
