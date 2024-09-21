<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_solicitud_motivo extends Model
{
    use LogsActivity;

    protected $table = 'archivo_solicitud_motivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'priorizar'
    ];

    protected static $logAttributes = [
        'name',
        'priorizar'
    ];
}
