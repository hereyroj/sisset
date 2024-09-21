<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class comparendo_infractor extends Model
{
    use LogsActivity;

    protected $table = 'comparendo_infractor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'telefono',
        'direccion',
        'licencia_numero',
        'licencia_fecha_vencimiento',
        'tipo_documento_id',
        'numero_documento',
        'comparendo_id',
        'direccion_runt',
        'telefono_runt',
        'licencia_categoria_id',
        'ciudad_id',
        'ciudad_runt_id',
        'direccion_electronica',
        'infractor_tipo_id',
        'infractor_clase_id'
    ];

    protected static $logAttributes = [
        'nombre',
        'telefono',
        'direccion',
        'licencia_numero',
        'licencia_fecha_vencimiento',
        'tipo_documento_id',
        'numero_documento',
        'comparendo_id',
        'direccion_runt',
        'telefono_runt',
        'licencia_categoria_id',
        'ciudad_id',
        'ciudad_runt_id',
        'direccion_electronica',
        'infractor_tipo_id',
        'infractor_clase_id'
    ];

    public function hasComparendo()
    {
        return $this->belongsTo('App\comparendo', 'comparendo_id');
    }

    public function hasTipoDocumento()
    {
        return $this->belongsTo('App\usuario_tipo_documento', 'tipo_documento_id');
    }

    public function hasCategoriaLicenciaConduccion()
    {
        return $this->belongsTo('App\licencia_categoria', 'licencia_categoria_id');
    }

    public function hasCiudad()
    {
        return $this->belongsTo('App\ciudad', 'ciudad_id');
    }

    public function hasCiudadRunt()
    {
        return $this->belongsTo('App\ciudad', 'ciudad_runt_id');
    }

    public function hasTipoInfractor()
    {
        return $this->belongsTo('App\comparendo_infractor_tipo', 'infractor_tipo_id');
    }
}
