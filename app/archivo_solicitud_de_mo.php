<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_solicitud_de_mo extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'archivo_solicitud_de_mo';

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

    public function hasSolicitudesDenegadas()
    {
        return $this->belongsToMany('App\archivo_solicitud', 'archivo_solicitud_denegacion','archivo_solicitud_de_mo_id', 'archivo_solicitud_id');
    }
}
