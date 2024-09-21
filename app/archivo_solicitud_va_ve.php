<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_solicitud_va_ve extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'archivo_solicitud_va_ve';

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

    public function hasSolicitudesValidadas()
    {
        return $this->belongsToMany('App\archivo_solicitud_va_ve', 'archivo_solicitud_validacion', 'archivo_solicitud_va_ve_id', 'archivo_solicitud_id');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
