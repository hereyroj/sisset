<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class trd_documento_tipo extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'trd_documento_tipo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trd_documento_subserie_id',
        'name',
    ];

    protected static $logAttributes = [
        'trd_documento_subserie_id',
        'name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function hasSubSerie()
    {
        return $this->belongsTo('App\trd_documento_subserie', 'trd_documento_subserie_id');
    }
}
