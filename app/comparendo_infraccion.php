<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class comparendo_infraccion extends Model
{
    use LogsActivity;

    protected $table = 'comparendo_infraccion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'descripcion',
        'comparendo_tipo_id',
        'inmoviliza',
        'smdlv'
    ];

    protected static $logAttributes = [
        'name',
        'descripcion',
        'comparendo_tipo_id',
        'inmoviliza',
        'smdlv'
    ];

    public function hasTipoComparendo()
    {
        return $this->belongsTo('App\comparendo_tipo', 'comparendo_tipo_id');
    }
}
