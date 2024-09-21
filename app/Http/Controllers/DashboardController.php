<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use App\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function notificaciones_sinLeer()
    {
        $notificaciones = auth()->user()->unreadNotifications;
        if ($notificaciones != null) {
            return view('admin.dashboard.notificaciones', ['notificaciones' => $notificaciones])->render();
        } else {
            return null;
        }
    }

    public function notificaciones_ultimas()
    {
        if($this->notificaciones_sinLeer() != null){
            $notificaciones = auth()->user()->unreadNotifications;
        }else{
            $notificaciones = auth()->user()->notifications->take(5);
        }

        if ($notificaciones != null) {
            return view('admin.dashboard.notificaciones', ['notificaciones' => $notificaciones])->render();
        } else {
            return null;
        }
    }

    public function notificaciones_obtenerTodas()
    {
        $notificaciones = auth()->user()->notifications;
        if ($notificaciones != null) {
            return view('admin.dashboard.notificaciones', ['notificaciones' => $notificaciones->take(30)])->render();
        } else {
            return null;
        }
    }

    public function obtenerNotificacion($id)
    {
        $notificaciones = auth()->user()->notifications;
        $notificacion = $notificaciones->filter(function ($item) use ($id) {
            return $item->id == $id;
        });
        $notificacion = $notificacion->first();
        $data = [
            'title' => trans('notifications.'.class_basename($notificacion->type).'.titulo', $notificacion->data),
            'description' => trans('notifications.'.class_basename($notificacion->type).'.descripcion', $notificacion->data),
        ];

        return json_encode($data);
    }

    public function notificaciones_marcarTodasLeidas()
    {
        return auth()->user()->unreadNotifications->markAsRead();
    }

    public function notificaciones_verNotificacion($notificacionId)
    {
        $notification = auth()->user()->getNotificationById($notificacionId);
        $notification->markAsRead();
        switch ($notification->type) {
            case 'App\Notifications\DBNuevoUsuario':
                return redirect('admin/sistema/usuarios/perfil/'.$notification->data['nuevoUsuario_id']);
            case 'App\Notifications\DBActualizarUsuario':
                return redirect('admin/sistema/usuarios/perfil/'.$notification->data['usuarioActualizado_id']);
            case 'App\Notifications\DBInformarUsuarioActualizado':
                return redirect('admin/cuenta/perfil/');
            case 'App\Notifications\PrevioAvisoPQR':
                return redirect('admin/pqr/administrar');
            case 'App\Notifications\FuncionarioPrevioAvisoPQR':
                return redirect('admin/pqr/misProcesos');
            case 'App\Notifications\RespuestaPQR':
                return redirect('admin/pqr/misProcesos');
            case 'App\Notifications\nuevaSolicitudPreAsignacion':
                return redirect('admin/tramites/preAsignaciones/administrar');
            case 'App\Notifications\AsignacionPQR':
                return redirect('admin/mis-pqr/misProcesos');
            case 'App\Notifications\ChatNewMessage':
                return redirect('admin/chat/openChatBox');    
            default: 
                return view('errors.404');    
        }
    }
}