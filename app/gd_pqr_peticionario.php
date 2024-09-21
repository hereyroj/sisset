<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_pqr_peticionario extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'gd_pqr_peticionario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gd_pqr_id',
        'funcionario_id',
        'dependencia_id',
        'tipo_documento_id',
        'departamento_id',
        'municipio_id',
        'correo_notificacion',
        'correo_electronico',
        'numero_telefono',
        'direccion_residencia',
        'numero_documento',
        'nombre_completo',
        'tipo_usuario',
    ];

    protected static $logAttributes = [
        'gd_pqr_id',
        'funcionario_id',
        'dependencia_id',
        'tipo_documento_id',
        'departamento_id',
        'municipio_id',
        'correo_notificacion',
        'correo_electronico',
        'numero_telefono',
        'direccion_residencia',
        'numero_documento',
        'nombre_completo',
        'tipo_usuario',
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

    public function getUsuarioTipoDocumento()
    {
        return $this->belongsTo('App\usuario_tipo_documento', 'tipo_documento_id');
    }

    public function couldHaveFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id')->with('hasDependencia');
    }

    public function couldHaveMunicipio()
    {
        return $this->belongsTo('App\ciudad', 'municipio_id');
    }

    public function couldHaveDpto()
    {
        return $this->belongsTo('App\departamento', 'departamento_id');
    }
}
