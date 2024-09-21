<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class comparendo_testigo extends Model
{
    use LogsActivity;

    protected $table = 'comparendo_testigo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'numero_documento',
        'direccion',
        'telefono',
        'tipo_documento_id',
        'comparendo_id'
    ];

    protected static $logAttributes = [
        'nombre',
        'numero_documento',
        'direccion',
        'telefono',
        'tipo_documento_id',
        'comparendo_id'
    ];

    public function hasComparendo()
    {
        return $this->belongsTo('App\comparendo', 'comparendo_id');
    }

    public function hasTipoDocumento()
    {
        return $this->belongsTo('App\usuario_tipo_documento', 'tipo_documento_id');
    }
}
