<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class acuerdo_pago extends Model
{
    use LogsActivity;

    protected $table = 'acuerdo_pago';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_acuerdo',
        'numero_acuerdo',
        'valor_total',
        'pago_inicial',
        'cuotas',
        'incumplido',
        'cancelado',
        'vigente',
        'acuerdo'
    ];

    protected static $logAttributes = [
        'fecha_acuerdo',
        'numero_acuerdo',
        'valor_total',
        'pago_inicial',
        'cuotas',
        'incumplido',
        'cancelado',
        'vigente',
        'acuerdo'
    ];

    public function hasCuotas()
    {
        return $this->hasMany('App\acuerdo_pago_cuota', 'acuerdo_pago_id');
    }

    public function hasDeudor()
    {
        return $this->hasOne('App\acuerdo_pago_deudor', 'acuerdo_pago_id');
    }

    public function getEstado()
    {
        if($this->hasSancion != null){
            return 'SANCIONADO';
        }elseif($this->incumplido){
            return 'INCUMPLIDO';
        }elseif($this->cancelado){
            return 'CANCELADO';
        }elseif($this->anulado){
            return 'ANULADO';
        }elseif($this->vigente){
            return 'VIGENTE';
        }else{
            return 'ERROR';
        }
    }

    public function hasComparendos()
    {
        return $this->morphedByMany('App\comparendo', 'proceso', 'acuerdo_pago_proceso');
    }

    public function hasMandamientosPagos()
    {
        return $this->morphedByMany('App\mandamiento_pago', 'proceso', 'acuerdo_pago_proceso');
    }

    public function hasMandamientoPago()
    {
        return $this->morphOne('App\mandamiento_pago', 'proceso');
    }

    public function hasSancion()
    {
        return $this->morphOne('App\sancion', 'proceso');
    }

    public function getMandamientosPagos()
    {
        return $this->belongsToMany(mandamiento_pago::class, 'acuerdo_pago_proceso', 'acuerdo_pago_id', 'proceso_id')->where('acuerdo_pago_proceso.proceso_type', mandamiento_pago::class);
    }

    public function getComparendos()
    {
        return $this->belongsToMany(comparendo::class, 'acuerdo_pago_proceso', 'acuerdo_pago_id', 'proceso_id')->where('acuerdo_pago_proceso.proceso_type', comparendo::class);
    }
}
