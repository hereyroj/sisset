<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_pqr_clasificacion extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'gd_pqr_clasificacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gd_pqr_id',
        'trd_documento_tipo_id',
    ];

    protected static $logAttributes = [
        'gd_pqr_id',
        'trd_documento_tipo_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasPQR()
    {
        return $this->belongsTo('App\gd_pqr', 'gd_pqr_id');
    }

    public function getDocumentoTipo()
    {
        return $this->belongsTo('App\trd_documento_tipo', 'trd_documento_tipo_id')->with('hasSubserie.hasSerie');
    }
}
