<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class sustrato_anulacion_motivo extends Model
{
    use LogsActivity;

    protected $table = 'sustrato_anulacion_motivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    protected static $logAttributes = [
        'name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function getSustratosAnulados()
    {
        return $this->belongsTo('App\sustrato', 'sustrato_anulacion', 'sustrato_anulacion_motivo_id', 'sustrato_id')->withPivot('observacion');
    }
}
