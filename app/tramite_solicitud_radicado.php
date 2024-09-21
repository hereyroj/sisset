<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_solicitud_radicado extends Model
{
    use LogsActivity;

    protected $table = 'tramite_solicitud_radicado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vigencia',
        'consecutivo',
        'tramite_solicitud_id',
    ];

    protected static $logAttributes = [
        'vigencia',
        'consecutivo',
        'tramite_solicitud_id',
    ];

    public function hasTramiteSolicitud()
    {
        return $this->belongsTo('App\tramite_solicitud', 'tramite_solicitud_id');
    }
}
