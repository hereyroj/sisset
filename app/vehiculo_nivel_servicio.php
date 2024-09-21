<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_nivel_servicio extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'vehiculo_nivel_servicio';

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

    public function hasManyTOS()
    {
        return $this->hasMany('App\tarjeta_operacion', 'nivel_servicio_id', 'id');
    }
}
