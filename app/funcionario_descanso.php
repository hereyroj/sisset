<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class funcionario_descanso extends Model
{
    use LogsActivity;

    protected $table = 'funcionario_descanso';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'funcionario_id',
        'fun_descanso_motivo_id'
    ];

    protected static $logAttributes = [
        'funcionario_id',
        'fun_descanso_motivo_id'
    ];

    public function hasMotivo()
    {
        return $this->belongsTo('App\funcionario_descanso_motivo', 'fun_descanso_motivo_id', 'id');
    }
}
