<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class mandamiento_medio extends Model
{
    use LogsActivity;

    protected $table = 'mandamiento_medio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'requiere_guia'
    ];

    protected static $logAttributes = [
        'name',
        'requiere_guia'
    ];
}
