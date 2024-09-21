<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class trd_documento_subserie extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'trd_documento_subserie';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trd_documento_serie_id',
        'name',
        'descripcion',
    ];

    protected static $logAttributes = [
        'trd_documento_serie_id',
        'name',
        'archivo_gestion',
        'archivo_central',
        'conservacion_total',
        'eliminacion',
        'digitalizar',
        'seleccion',
        'descripcion',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasTipos()
    {
        return $this->hasMany('App\trd_documento_tipo', 'trd_documento_subserie_id', 'id');
    }

    public function hasSerie()
    {
        return $this->belongsTo('App\trd_documento_serie', 'trd_documento_serie_id');
    }
}
