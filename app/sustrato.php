<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sustrato extends Model
{
    use LogsActivity;

    protected $table = 'sustrato';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'tipo_sustrato_id',
        'proceso_id',
        'proceso_type'
    ];

    protected static $logAttributes = [
        'numero',
        'consumido',
        'tipo_sustrato_id',
        'proceso_id',
        'proceso_type'
    ];

    public function hasAnulacion()
    {
        return $this->hasOne('App\sustrato_anulacion', 'sustrato_id');
    }

    public function hasConsumo()
    {
        return $this->morphTo('proceso');
    }

    public function hasTipoSustrato()
    {
        return $this->belongsTo('App\tipo_sustrato', 'tipo_sustrato_id');
    }

    public function hasTramiteFinalizacion()
    {
        return $this->belongsTo(tramite_servicio_finalizacion::class, 'proceso_id')->where('sustrato.proceso_type', tramite_servicio_finalizacion::class);
    }

    public function hasLicencia()
    {
        return $this->belongsTo(tramite_licencia::class, 'proceso_id')->where('sustrato.proceso_type', tramite_licencia::class);
    }

    public function hasLiberaciones()
    {
        return $this->hasMany('App\sustrato_liberacion', 'sustrato_id');
    }
}
