<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class gd_pqr_anulacion extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'gd_pqr_anulacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gd_pqr_id',
        'gd_pqr_anulacion_mo_id',
        'funcionario_id',
        'observation'
    ];

    protected static $logAttributes = [
        'gd_pqr_id',
        'gd_pqr_anulacion_mo_id',
        'funcionario_id',
        'observation'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasPQR()
    {
        return $this->belongsTo('App\gd_pqr', 'gd_pqr_id');
    }

    public function hasMotivo()
    {
        return $this->belongsTo('App\gd_pqr_anulacion_motivo', 'gd_pqr_anulacion_mo_id');
    }

    public function hasFuncionario()
    {
        return $this->belongsTo('App\User', 'funcionario_id');
    }
}
