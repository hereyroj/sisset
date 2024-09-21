<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sustrato_liberacion_motivo extends Model
{
    use LogsActivity;

    protected $table = 'sustrato_liberacion_motivo';

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
}
