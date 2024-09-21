<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sancion extends Model
{
    use LogsActivity;

    protected $table = 'sancion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero', 'fecha_sancion', 'cantidad_salarios', 'cuantia_salarios', 'documento', 'proceso_type', 'proceso_id', 'numero_proceso'
    ];

    protected static $logAttributes = [
        'numero', 'fecha_sancion', 'cantidad_salarios', 'cuantia_salarios', 'documento', 'proceso_type', 'proceso_id', 'numero_proceso'
    ];

    public function hasProceso(){
        return $this->morphTo('proceso');
    }
}
