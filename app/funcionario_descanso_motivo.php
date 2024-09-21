<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class funcionario_descanso_motivo extends Model
{
    use LogsActivity;

    protected $table = 'funcionario_descanso_motivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'minutes'
    ];

    protected static $logAttributes = [
        'name',
        'minutes'
    ];
}
