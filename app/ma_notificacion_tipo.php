<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ma_notificacion_tipo extends Model
{
    use LogsActivity;

    protected $table = 'ma_notificacion_tipo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'dia_cantidad',
        'dia_tipo'
    ];

    protected static $logAttributes = [
        'name',
        'dia_cantidad',
        'dia_tipo'
    ];
}
