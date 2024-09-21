<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_liquidacion_mes extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_liquidacion_mes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];

    protected static $logAttributes = [
        'nombre'
    ];

    public function hasVigencias()
    {
        return $this->belongsToMany('App\vehiculo_liquidacion_vigencia', 'vehiculo_liquidacion_vigencia_has_mes', 've_li_mes_id', 've_li_vi_id')->withPivot('porcentaje_interes');
    }
}