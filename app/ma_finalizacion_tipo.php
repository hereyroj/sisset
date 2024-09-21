<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ma_finalizacion_tipo extends Model
{
    use LogsActivity;

    protected $table = 'ma_finalizacion_tipo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cant'
    ];

    protected static $logAttributes = [
        'name'
    ];
}
