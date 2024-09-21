<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_carpeta_prestamo extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'archivo_carpeta_prestamo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'archivo_carpeta_id',
        'fecha_entrega',
        'funcionario_recibe_id',
        'funcionario_autoriza_id',
        'funcionario_entrega_id',
        'fecha_devolucion',
    ];

    protected static $logAttributes = [
        'archivo_carpeta_id',
        'fecha_entrega',
        'funcionario_recibe_id',
        'funcionario_autoriza_id',
        'funcionario_entrega_id',
        'fecha_devolucion',
    ];

    public function hasFuncionarioAutoriza()
    {
        return $this->belongsTo('App\User', 'funcionario_autoriza_id');
    }

    public function hasFuncionarioEntrega()
    {
        return $this->belongsTo('App\User', 'funcionario_entrega_id');
    }

    public function hasFuncionarioRecibe()
    {
        return $this->belongsTo('App\User', 'funcionario_recibe_id');
    }

    public function hasCarpeta()
    {
        return $this->belongsTo('App\archivo_carpeta', 'archivo_carpeta_id');
    }

    public function getEstado()
    {
        if ($this->fecha_devolucion == null && $this->fecha_entrega == null) {
            return 'Por entregar';
        } elseif ($this->fecha_entrega != null && $this->fecha_devolucion == null) {
            return 'Por regresar';
        } elseif ($this->fecha_devolucion != null) {
            return 'Regresada';
        } else {
            return 'Autorizada';
        }
    }
}
