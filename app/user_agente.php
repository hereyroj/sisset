<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class user_agente extends Model
{
    use LogsActivity;

    protected $table = 'user_agente';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'placa',
        'fecha_ingreso',
        'fecha_retiro',
        'estado',
        'comparendo_entidad_id'
    ];

    protected static $logAttributes = [
        'user_id',
        'placa',
        'fecha_ingreso',
        'fecha_retiro',
        'estado',
        'comparendo_entidad_id'
    ];

    public function hasUsuario()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function hasEntidad()
    {
        return $this->belongsTo('App\comparendo_entidad', 'comparendo_entidad_id');
    }
}
