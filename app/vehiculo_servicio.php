<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class vehiculo_servicio extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'vehiculo_servicio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'placa_consecutivo'
    ];

    protected static $logAttributes = [
        'name',
        'placa_consecutivo'
    ];

    public function hasClasesVinculadas(){
        return $this->belongsToMany('App\vehiculo_clase', 'vehiculo_clase_has_servicio', 'vehiculo_servicio_id', 'vehiculo_clase_id');
    }

    public function hasClaseVinculada($id){
        $clase = $this->hasClasesVinculadas()->where('id', $id)->first();
        if ($clase != null) {
            return $clase;
        } else {
            return null;
        }
    }

    public function hasPlacas()
    {
        return $this->hasMany('App\placa', 'vehiculo_servicio_id');
    }

    public function hasPreAsignaciones()
    {
        return $this->hasMany('App\solicitud_preasignacion','vehiculo_servicio_id');
    }

    public function hasVehiculos()
    {
        return $this->hasMany('App\Vehiculo','vehiculo_servicio_id','id');
    }
}
