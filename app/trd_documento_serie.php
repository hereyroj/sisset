<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class trd_documento_serie extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'trd_documento_serie';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dependencia_id',
        'name',
    ];

    protected static $logAttributes = [
        'dependencia_id',
        'name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasSubSeries()
    {
        return $this->hasMany('App\trd_documento_subserie', 'trd_documento_serie_id', 'id');
    }

    public function hasDependencia()
    {
        return $this->belongsTo('App\dependencia', 'dependencia_id');
    }
}
