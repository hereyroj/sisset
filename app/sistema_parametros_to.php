<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class sistema_parametros_to extends Model
{
    use LogsActivity;

    protected $connection = 'mysql_system';

    protected $table = 'parametros_to';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'consecutivo_inicial',
        'marca_agua',
        'valor_unitario',
        'vigencia_id'
    ];

    protected static $logAttributes = [
        'consecutivo_inicial',
        'marca_agua',
        'valor_unitario',
        'vigencia_id'
    ];

    public function hasVigencia()
    {
        return $this->belongsTo('App\sistema_parametros_vigencia', 'vigencia_id');
    }
}
