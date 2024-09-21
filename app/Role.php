<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Artesaos\Defender\Traits\Models\Role as RoleTrait;
use Artesaos\Defender\Contracts\Role as RoleInterface;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model implements RoleInterface
{
    use RoleTrait;
    use LogsActivity;

    protected static $logAttributes = [
        'name',
    ];

    public function tieneUsuarios()
    {
        return $this->belongsToMany('App\User', 'role_user', 'role_id', 'user_id');
    }

    public function hasPermisos()
    {
        return $this->belongsToMany('Artesaos\Defender\Permission', 'permission_role');
    }

    public function hasPermiso($permiso)
    {
        return $this->existPermission($permiso);
    }
}
