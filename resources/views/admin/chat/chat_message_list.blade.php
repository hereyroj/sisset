@foreach ($messages as $message) 
    @if($message->read_at == null) 

    @endif
    @if($message->sender_id != auth()->user()->id)
    <div class="incoming_msg msg" id="{{ $message->uuid }}" onscroll="marcarLeido($message->uuid)">
        <div class="received_msg">
            <div class="received_withd_msg">
                @if($message->hasAttachments->count() <= 0)
                <p>{{$message->message}}</p>
                @else
                <ul class="list-group">
                    @foreach ($message->hasAttachments as $file)
                    <li class="list-group-item"><i class="fas fa-file"></i> {{$file->original_name}} <a href="{{url('admin/chat/downloadFile/'.$file->uuid)}}"
                            class="chat_download_button"><i class="fas fa-arrow-circle-down"></i></a></li>
                    @endforeach
                </ul>
                @endif
                <span class="time_date"> {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->format('h:i:s A')}} | {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->diffForHumans()}}</span>
            </div>
        </div>
    </div>
    @else
    <div class="outgoing_msg msg" id="{{ $message->uuid }}" onscroll="marcarLeido($message->uuid)">
        <div class="sent_msg">
            @if($message->hasAttachments->count() <=0)
            <p>{{$message->message}}</p>
            @else
            <ul class="list-group">
                @foreach ($message->hasAttachments as $file)
                <li class="list-group-item"><i class="fas fa-file"></i> {{$file->original_name}} <a href="{{url('admin/chat/downloadFile/'.$file->uuid)}}"
                        class="chat_download_button"><i class="fas fa-arrow-circle-down"></i></a></li>
                @endforeach
            </ul>
            @endif
            <span class="time_date"> {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->format('h:i:s A')}} | {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->diffForHumans()}}</span>
        </div>
    </div>
    @endif
@endforeach