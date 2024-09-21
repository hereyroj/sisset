@foreach ($rooms as $room)
<div class="chat_list">
    <div class="chat_people">
        <div class="chat_img"> <img src="{{asset($room->logo)}}" alt="{{$room->name}}"> </div>
        <div class="chat_ib">
            @if($room->getLastMessage() != null)
            <h5>{{$room->name}} <span class="chat_date">{{$room->getLastMessage()->created_at->format('M t')}}</span></h5>
            <p>{{$room->getLastMessage()->message}}</p>
            @else
            <h5>{{$room->name}}</h5>
            @endif
        </div>
    </div>
</div>
@endforeach