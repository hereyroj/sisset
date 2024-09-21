<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class tramite_grupo extends Model
{
    use LogsActivity;

    protected $table = 'tramite_grupo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
    ];

    protected static $logAttributes = [
        'name',
        'code',
    ];

    public function hasTramites()
    {
        return $this->belongsToMany('App\tramite', 'tramite_grupo_has_tramite', 'tramite_grupo_id', 'tramite_id');
    }

    public function hasTramite($id)
    {
        $tramite = $this->hasTramites()->where('tramite_id', $id)->first();
        if($tramite != null){
            return true;
        }else{
            return false;
        }
    }
}
