<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_carpeta_traslado extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'archivo_carpeta_traslado';

    protected $fillable = [
        'fecha_traslado',
        'carpeta_id',
        'num_certificado_runt',
        'departamento_id',
        'municipio_id',
    ];

    protected static $logAttributes = [
        'fecha_traslado',
        'carpeta_id',
        'num_certificado_runt',
        'departamento_id',
        'municipio_id',
    ];

    public function departamentoTraslado()
    {
        return $this->hasOne('App\departamento', 'id', 'departamento_id');
    }

    public function municipioTraslado()
    {
        return $this->hasOne('App\ciudad', 'id', 'municipio_id');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
