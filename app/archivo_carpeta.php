<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_carpeta extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'archivo_carpeta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'available',
        'archivo_carpeta_estado_id',
        'vehiculo_clase_id',
        'radicado',
        'vehiculo_servicio_id',
    ];

    protected static $logAttributes = [
        'name',
        'available',
        'archivo_carpeta_estado_id',
        'vehiculo_clase_id',
        'radicado',
        'vehiculo_servicio_id',
    ];

    public function unAvailable()
    {
        $this->available = 'NO';

        return $this->save();
    }

    public function available()
    {
        $this->available = 'SI';

        return $this->save();
    }

    public function isAvailable()
    {
        if ($this->available == 'SI' && $this->hasSolicitudPendiente() == null) {
            return true;
        } else {
            return false;
        }
    }

    public function hasSolicitudPendiente()
    {
        $name = $this->name;
        $ultimoTrámite = tramite_solicitud::whereHas('hasServicios', function($query) use ($name){
            $query->where('placa', $name);
        })->orderBy('created_at', 'desc')->first();
        if($ultimoTrámite == null){
            return null;
        }

        if($ultimoTrámite->getEstadoSolicitud() != 'anulado' && $ultimoTrámite->getEstadoSolicitud() != 'finalizado'){
            return $ultimoTrámite;
        }else{
            return null;
        }
    }

    public function hasSolicitudes()
    {
        return tramite_solicitud::where('placa', $this->name)->orderBy('created_at', 'desc')->get();
    }

    public function couldHaveTraslado()
    {
        return $this->hasOne('App\archivo_carpeta_traslado', 'carpeta_id', 'id');
    }

    public function couldHaveCancelacion()
    {
        return $this->hasOne('App\archivo_carpeta_cancelacion', 'archivo_carpeta_id', 'id');
    }

    public function hasEstado()
    {
        return $this->belongsTo('App\archivo_carpeta_estado', 'archivo_carpeta_estado_id');
    }

    public function hasClase()
    {
        return $this->belongsTo('App\vehiculo_clase', 'vehiculo_clase_id');
    }

    public function hasServicio()
    {
        return $this->belongsTo('App\vehiculo_servicio', 'vehiculo_servicio_id');
    }

    public function hasPrestamos()
    {
        return $this->hasMany('App\archivo_carpeta_prestamo', 'archivo_carpeta_id');
    }

    public function hasPrestamoActivo()
    {
        return $this->hasPrestamos()->where('funcionario_recibe_id', '!=', null)->where('fecha_devolucion', null)->first();
    }

    public function hasPrestamoPendiente()
    {
        return $this->hasPrestamos()->where('funcionario_recibe_id', null)->first();
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
