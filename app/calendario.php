<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class calendario extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'calendario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dia',
        'fecha',
        'laboral',
        'fin_de_semana',
        'feriado',
        'descripcion',
    ];

    protected static $logAttributes = [
        'dia',
        'fecha',
        'laboral',
        'fin_de_semana',
        'feriado',
        'descripcion',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
