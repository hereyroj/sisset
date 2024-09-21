<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CoactivoFotoMultas extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'coactivo_foto_multa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cc',
        'pathArchive',
        'publication_date',
    ];

    protected static $logAttributes = [
        'name',
        'cc',
        'pathArchive',
        'publication_date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
