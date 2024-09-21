<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_servicio_estado extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'tramite_servicio_estado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'finaliza_servicio',
        'requiere_observacion',
    ];

    protected static $logAttributes = [
        'name',
        'finaliza_servicio',
        'requiere_observacion',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function getTramitesServicios()
    {
        return $this->belongsToMany('App\tramite_servicio', 'tramite_servicio_has_estado', 'tramite_servicio_estado_id', 'tramite_servicio_id')->withPivot('observacion');
    }
}
