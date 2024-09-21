<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_cilindraje_grupo extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_cilindraje_grupo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vigencia',
        'name',
        'vehiculo_clase_id',
        'desde',
        'hasta'
    ];

    protected static $logAttributes = [
        'vigencia',
        'name',
        'vehiculo_clase_id',
        'desde',
        'hasta'
    ];

    public function hasVehiculoClase()
    {
        return $this->belongsTo('App\vehiculo_clase', 'vehiculo_clase_id');
    }
}
