<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_bateria_grupo extends Model
{
    use LogsActivity;

    protected $table = 'vehiculo_bateria_grupo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vigencia',
        'name',
        'vehiculo_bateria_tipo_id',
        'desde',
        'hasta'
    ];

    protected static $logAttributes = [
        'vigencia',
        'name',
        'vehiculo_bateria_tipo_id',
        'desde',
        'hasta'
    ];

    public function hasTipoBateria()
    {
        return $this->belongsTo( 'App\vehiculo_bateria_tipo', 'vehiculo_bateria_tipo_id');
    }
}
