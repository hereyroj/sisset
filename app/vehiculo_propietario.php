<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_propietario extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_propietario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'numero_documento',
        'tipo_documento_id',
        'telefono',
        'departamento_id',
        'municipio_id',
        'direccion',
        'correo_electronico'
    ];

    protected static $logAttributes = [
        'nombre',
        'numero_documento',
        'tipo_documento_id',
        'telefono',
        'departamento_id',
        'municipio_id',
        'direccion',
        'correo_electronico'
    ];

    public function hasVehiculos()
    {
        return $this->belongsToMany('App\vehiculo', 'vehiculo_has_propietario', 'vehiculo_propietario_id', 'vehiculo_id')->withPivot('estado');
    }

    public function hasDepartamento()
    {
        return $this->belongsTo('App\departamento', 'departamento_id');
    }

    public function hasMunicipio()
    {
        return $this->belongsTo('App\ciudad', 'municipio_id');
    }

    public function hasTipoDocumento()
    {
        return $this->belongsTo('App\usuario_tipo_documento', 'tipo_documento_id');
    }

    public function hasVehiculosActivos()
    {
        return $this->belongsToMany('App\vehiculo', 'vehiculo_has_propietario', 'vehiculo_propietario_id', 'vehiculo_id')->withPivot('estado')->wherePivot('estado',1);
    }
}
