<?php
\Carbon\Carbon::setLocale('es');
?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Tiempo</th>
                <th>Clase modelo</th>
                <th>Descripción</th>
                <th>Usuario</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody id="tbody-activities">
            @foreach($logs as $log)
            <tr>
                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->created_at)->diffForHumans()}}</td>
                <td>{{$log->subject_type}}</td>
                <td>{{$log->description}}</td>
                <td>@if(isset($log->causer)){{$log->causer->name}}@endif</td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-secondary" onclick="verCambiosActividad({{$log->id}})">Ver cambios</button>                    @if(isset($log->causer) && $log->causer->id != auth()->user()->id)
                        <a href="{{url('admin/sistema/usuarios/eliminar/'.$log->causer->id)}}" class="btn btn-danger"><i class="fas fa-times"></i>  Bloquear usuario</a>                    @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$logs->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>