@foreach ($messages as $message) 
    @if($message->sender_id != auth()->user()->id)
    <div class="chat-element">
        <div class="media-body ">
            <small class="float-right text-navy">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->diffForHumans()}}</small>
            <strong>{{$message->hasSender->name}}</strong>
            <p class="m-b-xs">
                {{$message->message}}
            </p>
            <small class="text-muted">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->toDayDateTimeString()}}</small>
        </div>
    </div>
    @else
    <div class="chat-element right">
        <div class="media-body text-right">
            <small class="float-left">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->diffForHumans()}}</small>
            <p class="m-b-xs">
                {{$message->message}}
            </p>
            <small class="text-muted">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->toDayDateTimeString()}}</small>
            <small class="float-left"><a href="#" class="btn trash" onclick="borrarMensaje($message->uuid)" title="Eliminar"><i class="fas fa-trash"></i></a></small>
        </div>
    </div>
    @endif
@endforeach