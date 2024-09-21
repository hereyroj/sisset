<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_liquidacion_vigencia extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_liquidacion_vigencia';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vigencia',
        'impuesto_publico',
        'cantidad_meses_intereses',
        'derechos_entidad'
    ];

    protected static $logAttributes = [
        'vigencia',
        'impuesto_publico',
        'cantidad_meses_intereses',
        'derechos_entidad'
    ];

    public function hasMeses()
    {
        return $this->belongsToMany('App\vehiculo_liquidacion_mes', 'vehiculo_liquidacion_vigencia_has_mes', 've_li_vi_id', 've_li_mes_id')->withPivot('porcentaje_interes');
    }

    public function hasDescuentos()
    {
        return $this->belongsToMany('App\vehiculo_liquidacion_descuento', 'vehiculo_vig_des', 'vehiculo_liq_vig_id', 'vehiculo_liq_des_id');
    }

    public function getMesInteres($id)
    {
        return \DB::table('vehiculo_liquidacion_vigencia_has_mes')->select('porcentaje_interes')->where('ve_li_vi_id', $this->id)->where('ve_li_mes_id',$id)->value('porcentaje_interes');
    }
}
