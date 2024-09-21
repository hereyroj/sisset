<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sistema_parametros_empresa extends Model
{
    use LogsActivity;

    protected $connection = 'mysql_system';

    protected $table = 'parametros_empresa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vigencia_id',
        'empresa_logo_menu',
        'empresa_logo',
        'empresa_header',
        'empresa_map_coordinates',
        'empresa_nombre',
        'nombre_director',
        'firma_director',
        'empresa_sigla',
        'empresa_direccion',
        'empresa_telefono',
        'empresa_web',
        'empresa_correo_contacto'
    ];

    protected static $logAttributes = [
        'vigencia_id',
        'empresa_logo_menu',
        'empresa_logo',
        'empresa_header',
        'empresa_map_coordinates',
        'empresa_nombre',
        'nombre_director',
        'firma_director',
        'empresa_sigla',
        'empresa_direccion',
        'empresa_telefono',
        'empresa_web',
        'empresa_correo_contacto'
    ];

    public function hasVigencia()
    {
        return $this->belongsTo('App\sistema_parametros_vigencia', 'vigencia_id');
    }
}
