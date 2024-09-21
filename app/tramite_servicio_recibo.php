<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_servicio_recibo extends Model
{
    use LogsActivity;

    protected $table = 'tramite_servicio_recibo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cupl',
        'webservices',
        'observacion',
        'tramite_servicio_id',
        'consignacion',
        'numero_consignacion',
        'numero_sintrat',
        'numero_cupl'
    ];

    protected static $logAttributes = [
        'cupl',
        'webservices',
        'observacion',
        'tramite_servicio_id',
        'consignacion',
        'numero_consignacion',
        'numero_sintrat',
        'numero_cupl'
    ];

    public function hasTramiteServicio()
    {
        return $this->belongsTo('App\tramite_servicio', 'tramite_servicio_id');
    }
}
