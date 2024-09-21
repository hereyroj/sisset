<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_solicitud extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'archivo_solicitud';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'origen_id',
        'origen_type',
        'archivo_carpeta_prestamo_id',
    ];

    protected static $logAttributes = [
        'origen_id',
        'origen_type',
        'archivo_carpeta_prestamo_id',
    ];

    public function hasCarpetaPrestada()
    {
        return $this->belongsTo('App\archivo_carpeta_prestamo', 'archivo_carpeta_prestamo_id');
    }

    public function hasValidacion()
    {
        return $this->belongsToMany('App\archivo_solicitud_va_ve', 'archivo_solicitud_validacion', 'archivo_solicitud_id', 'archivo_solicitud_va_ve_id')->withPivot('observation')->withTimestamps();
    }

    public function hasDenegacion()
    {
        return $this->belongsToMany('App\archivo_solicitud_de_mo', 'archivo_solicitud_denegacion', 'archivo_solicitud_id', 'archivo_solicitud_de_mo_id')->withPivot('observation')->withTimestamps();
    }

    public function getEstado()
    {
        if ($this->hasDenegacion()->count() > 0) {
            return 'Denegada';
        } elseif ($this->hasValidacion()->count() > 0) {
            return 'Validada';
        } elseif ($this->hasCarpetaPrestada()->count() > 0) {
            return 'Carpeta prestada';
        } else {
            return 'Por procesar';
        }
    }

    public function hasOrigen()
    {
        return $this->morphTo('origen');
    }

    public function hasSolicitudFuncionario()
    {
        return $this->belongsTo(archivo_solicitud_funcionario::class, 'origen_id')->where('archivo_solicitud.origen_type', archivo_solicitud_funcionario::class);
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasTramiteServicio()
    {
        return $this->belongsTo(tramite_servicio::class, 'origen_id')->where('archivo_solicitud.origen_type', tramite_servicio::class);
    }
}
