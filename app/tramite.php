<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'tramite';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'requiere_sustrato',
        'solicita_carpeta',
        'tipo_sustrato_id',
        'requiere_placa',
        'cupl',
        'ministerio',
        'entidad',
        'sustrato'
    ];

    protected static $logAttributes = [
        'name',
        'requiere_sustrato',
        'solicita_carpeta',
        'tipo_sustrato_id',
        'requiere_placa',
        'cupl',
        'ministerio',
        'entidad',
        'sustrato'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasSolicitudes()
    {
        return $this->belongsToMany('App\tramite_solicitud', 'tramite_solicitud_has_tramite', 'tramite_id', 'tramite_solicitud_id');
    }

    public function hasTipoSustrato()
    {
        return $this->belongsTo('App\tipo_sustrato', 'tipo_sustrato_id');
    }

    public function hasRequerimientos()
    {
        return $this->hasMany('App\tramite_requerimiento', 'tramite_id');
    }

    public function hasGrupos()
    {
        return $this->belongsToMany('App\tramite_grupo', 'tramite_grupo_has_tramite', 'tramite_id', 'tramite_grupo_id');
    }
}
