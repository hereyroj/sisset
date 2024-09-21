@foreach ($usuarios as $usuario)
    <div class="chat_list" chatid="{{$usuario->id}}" chatorigen="User">
        <div class="chat_people">
            <div class="chat_img">
                <img src="{{asset($usuario->avatar)}}">
            </div>
            <div class="chat_ib">                
                <h5>{{$usuario->name}} <span class="chat_counter"></span>
                @if(auth()->user()->hasUnreadMessageFrom($usuario->id)->count() > 0)
                <span class="badge badge-danger">{{auth()->user()->hasUnreadMessageFrom($usuario->id)->count()}}</span>
                @endif
                </h5>                
                @if(auth()->user()->getLastMessageTo($usuario->id) != null && auth()->user()->getLastMessageFrom($usuario->id) != null)
                    @if(auth()->user()->getLastMessageTo($usuario->id)->created_at > auth()->user()->getLastMessageFrom($usuario->id)->created_at)
                        <p>{{ auth()->user()->getLastMessageTo($usuario->id)->message }}</p>
                    @else 
                        <p>{{ auth()->user()->getLastMessageFrom($usuario->id)->message }}</p>
                    @endif
                @elseif(auth()->user()->getLastMessageTo($usuario->id) != null)
                    <p>{{ auth()->user()->getLastMessageTo($usuario->id)->message }}</p>
                @elseif(auth()->user()->getLastMessageFrom($usuario->id) != null)
                    <p>{{ auth()->user()->getLastMessageFrom($usuario->id)->message }}</p>
                @else
                <p></p>
                @endif
            </div>
            
        </div>
    </div>
@endforeach