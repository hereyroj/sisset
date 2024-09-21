<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class mandamiento_pago extends Model
{
    use LogsActivity;

    protected $table = 'mandamiento_pago';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'consecutivo',
        'documento',
        'fecha_mandamiento',
        'proceso_id',
        'proceso_type',
        'valor'
    ];

    protected static $logAttributes = [
        'consecutivo',
        'documento',
        'fecha_mandamiento',
        'proceso_id',
        'proceso_type',
        'valor'
    ];

    public function hasProceso()
    {
        return $this->morphTo('proceso');
    }

    public function hasNotificaciones()
    {
        return $this->hasMany('App\mandamiento_notificacion', 'mandamiento_pago_id');
    }

    public function hasFinalizacion()
    {
        return $this->hasOne('App\mandamiento_finalizacion', 'mandamiento_pago_id');
    }

    public function hasAcuerdoPago()
    {
        return $this->morphToMany('App\acuerdo_pago', 'proceso', 'acuerdo_pago_proceso');
    }

    public function hasPago()
    {
        return $this->morphOne('App\cm_pago', 'proceso');
    }

    public function getEstado()
    {
        if($this->hasAcuerdoPago->count() > 0){
            return 'ACUERDO PAGO';
        }elseif($this->hasPago != null){
            return 'PAGADO';
        }elseif($this->hasFinalizacion != null){
            return 'FINALIZADO';
        }else{
            return 'EN PROCESO';
        }
    }

    public function getAcuerdoPago()
    {
        return $this->belongsTo(acuerdo_pago::class, 'proceso_id')->where('mandamiento_pago.proceso_type', acuerdo_pago::class);
    }

    public function getComparendo()
    {
        return $this->belongsTo(comparendo::class, 'proceso_id')->where('mandamiento_pago.proceso_type', comparendo::class);
    }
}
