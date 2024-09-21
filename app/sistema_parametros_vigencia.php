<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sistema_parametros_vigencia extends Model
{
    use LogsActivity;

    protected $connection = 'mysql_system';

    protected $table = 'vigencia';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vigencia',
        'salario_minimo',
        'impedir_cambios',
        'inicio_vigencia',
        'final_vigencia',
    ];

    protected static $logAttributes = [
        'vigencia',
        'salario_minimo',
        'impedir_cambios',
        'inicio_vigencia',
        'final_vigencia',
    ];

    public function hasEmpresa()
    {
        return $this->hasOne('App\sistema_parametros_empresa', 'vigencia_id');
    }

    public function hasPQR()
    {
        return $this->hasOne('App\sistema_parametros_pqr', 'vigencia_id');
    }

    public function hasTramite()
    {
        return $this->hasOne('App\sistema_parametros_tramites', 'vigencia_id');
    }

    public function hasGD()
    {
        return $this->hasOne('App\sistema_parametros_gd', 'vigencia_id');
    }

    public function hasTO()
    {
        return $this->hasOne('App\sistema_parametros_to', 'vigencia_id');
    }
}
