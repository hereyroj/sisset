<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_carroceria extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'vehiculo_carroceria';

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

    public function hasVehiculos()
    {
        return $this->hasMany('App\Vehiculo','vehiculo_carroceria_id','id');
    }
}
