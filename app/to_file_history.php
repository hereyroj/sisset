<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class to_file_history extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'to_file_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tarjeta_operacion_id',
        'status',
        'mime',
        'sha1',
        'name',
    ];

    protected static $logAttributes = [
        'tarjeta_operacion_id',
        'status',
        'mime',
        'sha1',
        'name',
    ];

    protected $dates = ['deleted_at'];

    public function hasTarjetaOperacion()
    {
        return $this->belongsTo('App\tarjeta_operacion', 'tarjeta_operacion_id');
    }
}
