<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_pqr_modalidad_envio extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'gd_pqr_modalidad_envio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'requiere_empresa',
    ];

    protected static $logAttributes = [
        'name', 'requiere_empresa',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
