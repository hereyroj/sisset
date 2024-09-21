<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_requerimiento extends Model
{
    use LogsActivity;

    protected $table = 'tramite_requerimiento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'tramite_id',
    ];

    protected static $logAttributes = [
        'name',
        'description',
        'tramite_id',
    ];

    public function hasTramite()
    {
        return $this->belongsTo('App\tramite', 'tramite_id');
    }
}
