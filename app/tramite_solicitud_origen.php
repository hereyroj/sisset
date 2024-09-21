<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_solicitud_origen extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'tramite_solicitud_origen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected static $logAttributes = [
        'name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function getTramitesSolicitudes()
    {
        return \DB::table('tramite_solicitud_origen')
            ->join('tramite_solicitud_turno', 'tramite_solicitud_origen.id', '=', 'tramite_solicitud_turno.tramite_solicitud_origen_id')
            ->join('tramite_solicitud', 'tramite_solicitud_turno.tramite_solicitud_id', '=', 'tramite_solicitud.id')
            ->select('tramite_solicitud.*')
            ->get();
    }
}
