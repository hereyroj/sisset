<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class comparendo extends Model
{
    use LogsActivity;

    protected $table = 'comparendo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'valor',
        'fecha_realizacion',
        'comparendo_infraccion_id',
        'comparendo_tipo_id',
        'observacion_agente',
        'agente_id',
        'documento',
        'barrio_vereda',
        'direccion',
        'fuga',
        'grado_alcoholemia',
        'niega_alcoholemia',
    ];

    protected static $logAttributes = [
        'numero',
        'valor',
        'fecha_realizacion',
        'comparendo_infraccion_id',
        'comparendo_tipo_id',
        'observacion_agente',
        'agente_id',
        'documento',
        'barrio_vereda',
        'direccion',
        'fuga',
        'grado_alcoholemia',
        'niega_alcoholemia',
    ];

    public function hasVehiculo()
    {
        return $this->hasOne('App\comparendo_vehiculo', 'comparendo_id');
    }

    public function hasInfractor()
    {
        return $this->hasOne('App\comparendo_infractor', 'comparendo_id');
    }

    public function hasInfraccion()
    {
        return $this->belongsTo('App\comparendo_infraccion', 'comparendo_infraccion_id');
    }

    public function hasTipoComparendo()
    {
        return $this->belongsTo('App\comparendo_tipo', 'comparendo_tipo_id');
    }

    public function hasPago()
    {
        return $this->morphOne('App\cm_pago', 'proceso');
    }

    public function hasAgente()
    {
        return $this->belongsTo('App\user_agente', 'agente_id');
    }

    public function hasTipoInmovilizacion()
    {
        return $this->belongsToMany('App\comparendo_inmovilizacion_tipo', 'comparendo_inmovilizacion', 'comparendo_id', 'inmovilizacion_tipo_id')->withPivot('observacion')->withTimestamps();
    }

    public function hasSancion()
    {
        return $this->morphOne('App\sancion', 'proceso');
    }

    public function hasAcuerdoPago()
    {
        return $this->morphToMany('App\acuerdo_pago', 'proceso', 'acuerdo_pago_proceso');
    }

    public function hasMandamientoPago()
    {
        return $this->morphOne('App\mandamiento_pago', 'proceso');
    }

    public function getEstado()
    {
        if($this->hasPago() != null){
            return 'PAGADO';
        }elseif($this->hasAcuerdoPago()->count() > 0){
            return 'ACUERDO PAGO';
        }elseif($this->hasMandamientoPago() != null){
            return 'MANDAMIENTO PAGO';
        }else{
            return 'POR PAGAR';
        }
    }
}
