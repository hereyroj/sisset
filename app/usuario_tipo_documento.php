<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Tylercd100\LERN\Models\ExceptionModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class usuario_tipo_documento extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'usuario_tipo_documento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'requiere_numero'
    ];

    protected static $logAttributes = [
        'name', 'requiere_numero'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
