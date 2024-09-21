@if(count($notificaciones)>0)
    @foreach ($notificaciones as $notificacion)
        <a href="{{ url("admin/notificaciones/ver/{$notificacion->id}") }}" class="list-group-item @if($notificacion->read_at != null) item-info @else unread @endif" style="border-radius: 0px">
            <h5>{{ trans('notifications.'.class_basename($notificacion->type).'.titulo', $notificacion->data) }}</h5>
            <p class="list-group-item-text">
                {!! trans('notifications.'.class_basename($notificacion->type).'.descripcion', $notificacion->data) !!}
                <br>
                <strong>
                    <?php
                    \Carbon\Carbon::setLocale('es');
                    echo \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notificacion->created_at)->diffForHumans();
                    ?>
                </strong>
            </p>
        </a>
    @endforeach
@else
    <a href="#" class="list-group-item">Sin notificaciones</a>
@endif