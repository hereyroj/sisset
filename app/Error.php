<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tylercd100\LERN\Models\ExceptionModel;
use App\User;

class Error extends ExceptionModel
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
