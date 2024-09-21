<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\chat_message;
use App\User;
use Webpatser\Uuid\Uuid;
use App\chat_room;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ChatNewMessage;
use App\chat_message_attach;

class ChatController extends Controller
{
    public function openChatBox()
    {
        $users = User::where('lock_session', 'no')->where('id', '!=', auth()->user()->id)->get();;
        return view('admin.chat.chat_box', ['users' => $users]);
    }

    public function obtenerMensajes($origen, $id)
    {
        $messages = chat_message::with('hasAttachments')->where('receiver_type', 'App\\'.$origen)->where('receiver_id', $id)->where('sender_id', auth()->user()->id)->orWhere('sender_id', $id)->where('receiver_id', auth()->user()->id)->get(); 
        return view('admin.chat.chat_message_list', ['messages' => $messages])->render();       
    }

    public function enviarMensaje(Request $request)
    {
        try{
            $message = chat_message::create([
                'uuid' => Uuid::generate(5, str_random(5) . 'App\\' . $request->origen . '-' . $request->id . '-' . date('Y-m-d H:i:s'), Uuid::NS_DNS),
                'message' => $request->mensaje,
                'sender_id' => auth()->user()->id,
                'receiver_id' => $request->id,
                'receiver_type' => 'App\\' . $request->origen,
            ]);
            if($request->origen == 'User'){
                \Notification::send(User::find($request->id), new ChatNewMessage($message));
            }elseif($request->origen == 'chat_room'){
                $usuarios = chat_room::find($request->id)->hasActiveUsers();
                \Notification::send($usuarios, new ChatNewMessage($message));
            }            
            return json_encode((string) $message->uuid);
        }catch(\Exception $e){
            return false;
        }
    }

    public function obtenerMensaje($id)
    {
        $message = chat_message::with('hasAttachments')->where('uuid', str_replace('"', '', $id))->first();
        return view('admin.chat.chat_message', ['message' => $message])->render();
    }

    public function ingrearChatRoom($id)
    {

    }

    public function abandonarChatRoom($id)
    {
        $chatRoom = chat_room::where('uuid', $id)->first();
        return $chatRoom->leaveUser(auth()->user()->id);
    }

    public function nuevoChatRoom()
    {

    }

    public function crearChatRoom()
    {

    }

    public function obtenerUsuarios()
    {
        $usuarios = User::where('id', '!=', auth()->user()->id)->where('lock_session', 'no')->orderBy('name')->get();
        return view('admin.chat.chat_users_list', ['usuarios'=>$usuarios])->render();
    }

    public function obtenerRooms()
    {
        $rooms = chat_room::all();
        return view('admin.chat.chat_rooms_list', ['rooms' => $rooms])->render();
    }

    public function enviarArchivos(Request $request)
    {
        try{
            $message = chat_message::create([
                'uuid' => Uuid::generate(5, str_random(5) . 'App\\' . $request->chat_origen . '-' . $request->chat_id . '-' . date('Y-m-d H:i:s'), Uuid::NS_DNS),
                'message' => 'itsfiles',
                'sender_id' => auth()->user()->id,
                'receiver_id' => $request->chat_id,
                'receiver_type' => 'App\\' . $request->chat_origen,
            ]);            
            foreach ($request->attachments as $archivo) {
                try {
                    $name = \Storage::putFile('chats', $archivo);
                    chat_message_attach::create([
                        'chat_message_id' => $message->id,
                        'name' => $name,
                        'original_name' => $archivo->getClientOriginalName(),
                        'mime' => mime_content_type(storage_path('app/' . $name)),
                        'uuid' => Uuid::generate(5, str_random(5) . 'App\\chat_message_attach' . $request->chat_id . '-' . date('Y-m-d H:i:s'), Uuid::NS_DNS),
                    ]);
                } catch (\Exception $e) {
                    
                }
            }
            if ($request->chat_origen == 'User') {
                \Notification::send(User::find($request->chat_id), new ChatNewMessage($message));
            } elseif ($request->chat_origen == 'chat_room') {
                $usuarios = chat_room::find($request->chat_id)->hasActiveUsers();
                \Notification::send($usuarios, new ChatNewMessage($message));
            }
            return json_encode((string) $message->uuid);
        }catch(\Exception $e){
            return false;
        }
    }

    public function downloadFile($id)
    {
        $file = chat_message_attach::where('uuid', $id)->first();
        if($file->hasMessage->sender_id == auth()->user()->id || $file->hasMessage->receiver_id == auth()->user()->id){
            $name = explode('/', $file->name);
            $headers = [
                'Content-Type: application/pdf',
                'Content-Disposition: attachment; filename="' . array_last($name) . '"',
            ];

            return Response()->download(storage_path('app/' . $file->name), array_last($name), $headers);
        }else{
            return view('errors.403', ['message'=>'No tiene permisos para acceder a este archivo.']);
        }
    }

    public function markAsRead($id)
    {
        $message = chat_message::with('hasAttachments')->where('uuid', $id)->first();
        if ($message->reat_at == null) {
            $message->reat_at = date('Y-m-d H:i:s');
            $message->save();
        } 
    }
}
