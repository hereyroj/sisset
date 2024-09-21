<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_solicitud_usuario extends Model
{
    use LogsActivity;

    protected $table = 'tramite_solicitud_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_usuario',
        'numero_documento',
        'correo_electronico',
        'numero_telefonico',
        'tramite_solicitud_turno_id',
        'tipo_documento_identidad_id',
    ];

    protected static $logAttributes = [
        'tramite_solicitud_turno_id',
        'nombre_usuario',
        'numero_documento',
        'correo_electronico',
        'numero_telefonico',
        'tipo_documento_identidad_id',
    ];

    public function hasTipoDocumentoIdentidad()
    {
        return $this->belongsTo('App\usuario_tipo_documento', 'tipo_documento_identidad_id');
    }

    public function hasFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id');
    }
}
