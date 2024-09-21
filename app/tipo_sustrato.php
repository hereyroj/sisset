<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tipo_sustrato extends Model
{
    use LogsActivity;

    protected $table = 'tipo_sustrato';

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

    public function hasSustratos()
    {
        return $this->hasMany('App\sustrato', 'tipo_sustrato_id');
    }
}
