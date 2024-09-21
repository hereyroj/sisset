<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_solicitud_documentacion extends Model
{
    use LogsActivity;

    protected $table = 'tramite_solicitud_documentacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tramite_solicitud_id',
        'ruta_documento',
    ];

    protected static $logAttributes = [
        'tramite_solicitud_id',
        'ruta_documento',
    ];
}
