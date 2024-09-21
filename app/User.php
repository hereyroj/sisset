<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Artesaos\Defender\Traits\HasDefender;
use Artesaos\Defender\Permission;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mockery\Exception;
use Monolog\ErrorHandler;
use Spatie\Activitylog\Traits\LogsActivity;
use Tylercd100\LERN\Models\ExceptionModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasDefender;
    use Notifiable;
    use SoftDeletes;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'password',
        'dependencia_id',
        'avatar',
        'lock_session',
        'email',
        'google2fa_secret',
    ];

    protected static $logAttributes = [
        'name',
        'password',
        'dependencia_id',
        'avatar',
        'lock_session',
        'email',
        'google2fa_secret',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pin_code',
        'google2fa_secret',
    ];

    public function getPermisoAgregado($id)
    {
        return $this->couldHavePermisosAgregados()->where('permission_id', $id)->first();
    }

    public function getPermisoAgregadoNombre($nombre)
    {
        return $this->couldHavePermisosAgregados()->where('name', $nombre)->first();
    }

    public function havePermisoAgregado($id)
    {
        if ($this->couldHavePermisosAgregados()->where('id', $id)->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function hasRoles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($name)
    {
        $role = $this->hasRoles()->where('name', $name)->first();
        if ($role != null) {
            return true;
        } else {
            return false;
        }
    }

    public function hasAnyRoles($roles = array())
    {
        $has = false;
        foreach ($roles as $role) {
            $rol = $this->hasRoles()->where('name', $role)->first();
            if ($rol != null) {
                $has = true;
            }
        }
        return $has;
    }

    public function couldHavePermisosAgregados()
    {
        return $this->belongsToMany('Artesaos\Defender\Permission', 'permission_user')->withPivot('value', 'expires');
    }

    public function hasDependencia()
    {
        return $this->hasOne('App\dependencia', 'id', 'dependencia_id');
    }

    public function deleteAllPermisos()
    {
        /*
         * Se eliminarán primero los permisos agregados
         */
        $permisos = $this->couldHavePermisosAgregados()->get();
        if ($permisos->count() > 0) {
            foreach ($permisos as $permiso) {
                $permiso->pivot->delete();
            }
        }
    }

    public function puedeHacerlo($permiso)
    {
        if ($this->couldHavePermisosAgregados()->where('name', $permiso)->count() > 0) {
            $permiso = $this->getPermisoAgregadoNombre($permiso);
            if ($permiso->pivot->value == 1 || $permiso->pivot->value == true) {
                if ($permiso->pivot->expires != null) {
                    $fecha = new Carbon();
                    $fecha->createFromFormat('Y-m-d H:i:s', $permiso->pivot->expires);
                    //echo Carbon::now('America/Bogota')->diffInSeconds(Carbon::createFromFormat('Y-m-d H:i:s', $permiso->pivot->expires), false);
                    if (Carbon::now('America/Bogota')->diffInSeconds(Carbon::createFromFormat('Y-m-d H:i:s', $permiso->pivot->expires), false) <= 0) {
                        $permiso->pivot->value = 0;
                        $permiso->pivot->save();
                        $permiso = null;

                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } elseif ($this->roleHasPermission($permiso)) {
            return true;
        } else {
            return false;
        }
    }

    public function getNotificationById($notificationId)
    {
        return $this->notifications()->where('id', '=', $notificationId)->first();
    }

    public function errors()
    {
        return $this->hasMany(ExceptionModel::class);
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function hasVentanillasAsignadas()
    {
        return $this->belongsToMany('App\ventanilla', 'ventanilla_funcionario', 'funcionario_id', 'ventanilla_id')->withPivot('libre', 'fecha_ocupacion', 'fecha_retiro', 'funcionario_id', 'ventanilla_id');
    }

    public function hasVentanillaAsignacionActiva()
    {
        return $this->hasVentanillasAsignadas()->wherePivot('fecha_ocupacion', date('Y-m-d'))->wherePivot('libre', 'NO')->wherePivot('fecha_retiro', null)->first();
    }

    public function hasVentanillaAsignada($ventanilla_id)
    {
        $ventanilla = $this->hasVentanillasAsignadas()->wherePivot('ventanilla_id', $ventanilla_id)->first();
        if ($ventanilla instanceof ventanilla) {
            return true;
        } else {
            return false;
        }
    }

    public function asignarVentanilla($ventanilla_id)
    {
        try {
            $ventanillaActiva = $this->hasVentanillaAsignacionActiva();
            if ($ventanillaActiva != null) {
                $ventanillaActiva->pivot->fecha_retiro = date('Y-m-d H:i:s');
                $ventanillaActiva->pivot->save();
            }
            $this->hasVentanillasAsignadas()->attach($ventanilla_id, [
                'fecha_ocupacion' => date('Y-m-d'),
                'libre' => 'NO',
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function hasTurnosAsignados()
    {
        return $this->belongsToMany('App\tramite_solicitud_turno', 'tramite_solicitud_asignacion', 'funcionario_id', 'tramite_solicitud_turno_id');
    }

    public function hasTurnoActivo()
    {
        return $this->hasTurnosAsignados()->doesntHave('hasAtencion')->where('fecha_llamado', '!=', null)->orderBy('created_at', 'asc')->first();
    }

    public function cerrarVentanilla()
    {
        $ventanillaActiva = $this->hasVentanillaAsignacionActiva();
        if ($ventanillaActiva != null) {
            $ventanillaActiva->pivot->fecha_retiro = date('Y-m-d H:i:s');
            $ventanillaActiva->pivot->libre = 'SI';
            $ventanillaActiva->pivot->save();
        }
    }

    public function hasAgente()
    {
        return $this->hasOne('App\user_agente', 'user_id')->where('estado', 1)->where('fecha_retiro', null);
    }

    /**
     * Ecrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function setGoogle2faSecretAttribute($value)
    {
        $this->attributes['google2fa_secret'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function getGoogle2faSecretAttribute($value)
    {
        if ($value != null) {
            return decrypt($value);
        }
        return null;
    }

    public function hasU2f()
    {
        return $this->hasMany('LaravelWebauthn\Models\WebauthnKey', 'user_id');
    }

    public function hasChatRooms()
    {
        return $this->balongsToMany('App\chat_room', 'user_id', 'chat_room_id')->withPivot('leave', 'leave_at', 'admin');
    }

    public function hasAccesToChatRoom($roomId)
    {
        if($this->hasChatRooms()->where('chat_room.id', $roomId)->wherePivot('leave', false)->wherePivot('leavet_at', null)->first() != null){
            return true;
        }else{
            return false;
        }
    }

    public function getLastMessageTo($id)
    {
        return chat_message::where('sender_id', auth()->user()->id)->where('receiver_id', $id)->orderBy('created_at', 'desc')->first();
    }

    public function getLastMessageFrom($id)
    {
        return chat_message::where('sender_id', $id)->where('receiver_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
    }

    public function hasUnreadMessageFrom($id)
    {
        return chat_message::where('sender_id', $id)->where('receiver_id', auth()->user()->id)->where('read_at', null)->orderBy('created_at', 'desc')->get();
    }
}
