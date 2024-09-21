<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class normativa extends Model
{
    use LogsActivity;

    protected $table = 'normativa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'fecha_expedicion',
        'objeto',
        'documento',
        'normativa_tipo_id'
    ];

    protected static $logAttributes = [
        'numero',
        'fecha_expedicion',
        'objeto',
        'documento',
        'normativa_tipo_id'
    ];

    public function hasTipo()
    {
        return $this->belongsTo('App\normativa_tipo', 'normativa_tipo_id');
    }
}
