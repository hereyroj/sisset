<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class tramite_licencia extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'tramite_licencia';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tramite_solicitud_id',
        'sustrato_id',
        'funcionario_id',
        'turno_id',
        'consignacion',
        'cupl',
        'webservices',
        'numero_consignacion',
        'numero_cupl',
        'numero_sintrat'
    ];

    protected static $logAttributes = [
        'tramite_solicitud_id',
        'sustrato_id',
        'funcionario_id',
        'turno_id',
        'cupl',
        'webservices',
        'numero_consignacion',
        'numero_cupl',
        'numero_sintrat'
    ];

    public function hasTramiteSolicitud()
    {
        return $this->belongsTo('App\tramite_solicitud', 'tramite_solicitud_id');
    }

    public function hasSustrato()
    {
        return $this->morphOne('App\sustrato', 'proceso');
    }

    public function hasCategorias()
    {
        return $this->belongsToMany('App\licencia_categoria', 'tramite_licencia_categoria', 'tramite_licencia_id', 'licencia_categoria_id');
    }

    public function hasFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id');
    }

    public function hasTurno()
    {
        return $this->belongsTo('App\tramite_solicitud_turno', 'turno_id', 'id');
    }
}
