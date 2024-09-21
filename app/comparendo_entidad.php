<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class comparendo_entidad extends Model
{
    use LogsActivity;

    protected $table = 'comparendo_entidad';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    protected static $logAttributes = [
        'name'
    ];
}
