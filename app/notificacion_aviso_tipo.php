<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class notificacion_aviso_tipo extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'notificacion_aviso_tipo';

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

    public function hasSanciones()
    {
        return $this->hasMany('App\sancion', 'sancion_tipo_id');
    }
}
