<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_carpeta_cancelacion extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'archivo_carpeta_cancelacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'archivo_carpeta_id',
        'nro_certificado_runt',
        'fecha_cancelacion',
        'nombre_funcionario_autoriza',
        'motivo_id',
    ];

    protected static $logAttributes = [
        'archivo_carpeta_id',
        'nro_certificado_runt',
        'fecha_cancelacion',
        'nombre_funcionario_autoriza',
        'motivo_id',
    ];

    public function hasCapeta()
    {
        return $this->hasMany('App\archivo_carpeta', 'archivo_carpeta_id', 'id');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
