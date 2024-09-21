<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class gd_medio_traslado extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'gd_medio_traslado';

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

    public function hasPQRS()
    {
        return $this->hasMany('App\gd_pqr', 'gd_medio_traslado_id');
    }

    public function countPQRS()
    {
        return $this->hasPQRS()->count();
    }
}
