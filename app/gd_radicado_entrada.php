<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_radicado_entrada extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'gd_radicado_entrada';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'origen',
        'origen_id',
        'origen_type',
    ];

    protected static $logAttributes = [
        'numero',
        'origen',
        'origen_id',
        'origen_type',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasOrigen()
    {
        return $this->morphTo();
    }
}
