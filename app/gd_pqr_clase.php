<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_pqr_clase extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'gd_pqr_clase';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'dia_clase',
        'dia_cantidad',
        'required_answer',
    ];

    protected static $logAttributes = [
        'name',
        'dia_clase',
        'dia_cantidad',
        'required_answer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasPQRS()
    {
        return $this->hasMany('App\gd_pqr', 'gd_pqr_clase_id');
    }

    public function countPQRS()
    {
        return $this->hasPQRS()->count();
    }
}
