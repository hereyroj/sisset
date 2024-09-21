<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class archivo_carpeta_ca_mo extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'archivo_carpeta_ca_mo';

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

    public function hasCarpetas()
    {
        return $this->hasManyThrough('App\archivo_carpeta', 'App\archivo_carpeta_cancelacion', 'archivo_carpeta_id', 'motivo_id', 'id');
    }

    public function countCarpetas()
    {
        return $this->hasCarpetas()->count();
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
