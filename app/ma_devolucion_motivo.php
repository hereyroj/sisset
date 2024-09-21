<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ma_devolucion_motivo extends Model
{
    use LogsActivity;

    protected $table = 'ma_devolucion_motivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    protected static $logAttributes = [
        'name'
    ];
}
