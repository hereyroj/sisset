<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_pqr_entrega extends Model
{
    use LogsActivity;

    protected $table = 'gd_pqr_entrega';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gd_pqr_id',
        'fecha_entrega',
        'documento_entrega',
    ];

    protected static $logAttributes = [
        'gd_pqr_id',
        'fecha_entrega',
        'documento_entrega',
    ];

    public function hasPQR()
    {
        return $this->belongsTo('App\gd_pqr', 'gd_pqr_id');
    }
}
