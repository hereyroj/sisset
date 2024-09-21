<?php
\Carbon\Carbon::setLocale('es');
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Tiempo</th>
            <th>Clase</th>
            <th>Archivo</th>
            <th>Linea</th>
            <th>Mensaje</th>
            <th>Enlace</th>
            <th>Usuario</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody id="tbody-exceptions">
        @foreach($excepciones as $exception)
        <tr>
            <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $exception->created_at)->diffForHumans()}}</td>
            <td>{{$exception->class}}</td>
            <td>{{$exception->file}}</td>
            <td>{{$exception->line}}</td>
            <td>{{$exception->message}}</td>
            <td>{{$exception->url}}</td>
            <td>@if(isset($exception->user)){{$exception->user->name}}@endif</td>
            <td>
                @if(isset($exception->user) && $exception->user->id != auth()->user()->id)
                <a href="{{url('admin/sistema/usuarios/eliminar/'.$exception->user->id)}}" class="btn btn-danger"><i class="fas fa-times"></i> Bloquear usuario</a>                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    <div class="text-center">
        {{$excepciones->links('vendor.pagination.bootstrap-4')}}
    </div>
</table>