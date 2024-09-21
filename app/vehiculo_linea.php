<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_linea extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_linea';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'cilindraje',
        'vehiculo_marca_id',
        'watts'
    ];

    protected static $logAttributes = [
        'nombre',
        'cilindraje',
        'vehiculo_marca_id',
        'watts'
    ];

    public function hasMarca()
    {
        return $this->belongsTo('App\vehiculo_marca', 'vehiculo_marca_id');
    }

    public function hasVehiculos()
    {
        return $this->hasMany('App\vehiculo', 'vehiculo_linea_id');
    }
}
