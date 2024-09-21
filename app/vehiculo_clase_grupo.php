<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_clase_grupo extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_clase_grupo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vigencia',
        'name',
        'vehiculo_clase_id',
        'vehiculo_marca_id'
    ];

    protected static $logAttributes = [
        'vigencia',
        'name',
        'vehiculo_clase_id',
        'vehiculo_marca_id'
    ];

    public function hasVehiculoClase()
    {
        return $this->belongsTo('App\vehiculo_clase', 'vehiculo_clase_id');
    }

    public function hasVehiculoMarca()
    {
        return $this->belongsTo('App\vehiculo_marca', 'vehiculo_marca_id');
    }
}
