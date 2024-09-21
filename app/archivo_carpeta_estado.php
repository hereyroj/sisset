<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_carpeta_estado extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'archivo_carpeta_estado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'estado_carpeta',
    ];

    protected static $logAttributes = [
        'name',
        'estado_carpeta',
    ];

    public function hasCarpetas()
    {
        return $this->hasMany('App\archivo_carpeta', 'archivo_carpeta_estado_id');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
