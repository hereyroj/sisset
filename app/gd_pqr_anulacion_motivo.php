<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_pqr_anulacion_motivo extends Model
{
    use LogsActivity;

    protected $table = 'gd_pqr_anulacion_motivo';

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
}
