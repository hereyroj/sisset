<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_liq_base_gravable extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_liq_base_gravable';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vehiculo_linea_id',
        'modelo',
        'vigencia',
        'avaluo',
        'grupo',
        'tonelaje',
        'pasaje',
        'otro',
        'descripcion',
        'vehiculo_marca_id'
    ];

    protected static $logAttributes = [
        'vehiculo_linea_id',
        'modelo',
        'vigencia',
        'avaluo',
        'grupo',
        'tonelaje',
        'pasaje',
        'otro',
        'descripcion',
        'vehiculo_marca_id'
    ];

    public function hasVehiculoLinea()
    {
        return $this->belongsTo('App\vehiculo_linea', 'vehiculo_linea_id');
    }

    public function hasVehiculoClase()
    {
        return $this->belongsTo('App\vehiculo_clase', 'vehiculo_clase_id');
    }

    public function hasVehiculoMarca()
    {
        return $this->belongsTo('App\vehiculo_marca', 'vehiculo_marca_id');
    }
}
