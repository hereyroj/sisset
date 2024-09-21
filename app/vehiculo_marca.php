<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class vehiculo_marca extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'vehiculo_marca';

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
        return $this->hasMany('App\vehiculo','vehiculo_marca_id','id');
    }

    public function hasClases()
    {
        return $this->belongsToMany('App\vehiculo_clase', 'vehiculo_marca_has_clase', 'vehiculo_marca_id', 'vehiculo_clase_id');
    }
}
