<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{userId}', function ($user, $userId) {
    return auth()->user()->id === $user->id;
});

Broadcast::channel('App.PQR', function ($user) {
    if ($user->id === auth()->user()->id && $user->hasAnyRoles(['Administrador PQR', 'Administrador'])) {
        return true;
    } else {
        return false;
    }
});

Broadcast::channel('App.misPQR.{funcionarioId}', function ($user, $funcionarioId) {
    try{
        if($user->id === (int) $funcionarioId && $user->id == auth()->user()-id){
            return true;
        }else{
            return false;
        }
    }catch (\Exception $e){
        return false;
    }
});

Broadcast::channel('App.Solicitudes', function ($user) {
    if (($user->id === auth()->user()->id) && $user->hasAnyRoles(['Coordinador Archivo','Administrador','Auxiliar Archivo'])) {
        return true;
    } else {
        return false;
    }
});

Broadcast::channel('App.PreAsignaciones', function ($user) {
    if ($user->id === auth()->user()->id && $user->hasAnyRoles(['Coordinador Trámites','Administrador', 'Auxiliar Trámites'])) {
        return true;
    } else {
        return false;
    }
});

Broadcast::channel('App.TramitesSolicitudes', function ($user) {
    if ($user->id === auth()->user()->id && $user->hasRole('Administrador', 'Coordinador Trámites', 'Auxiliar Trámites')) {
        return true;
    } else {
        return false;
    }
});

Broadcast::channel('App.Turnos', function ($user) {
    return auth()->user()->id === $user->id;
});

Broadcast::channel('App.Sesiones', function ($user) {
    return auth()->user()->id === $user->id;
});