<div id="estadosServicio">
<div class="cabecera-tabla">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary" onclick="obtenerEstadosServicioModal({{$id}})">
            <i class="fas fa-sync"></i> Actualizar
        </button>
        <button type="button" onclick="asignarEstadoServicio({{$id}})" class="btn btn-info">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Asignar
        </button>
    </div>
</div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><input type="checkbox" name="selectAll" id="selectAll" onchange="selectAll(this);"></th>
                    <th>Estado</th>
                    <th>Observación</th>
                    <th>Realizado por</th>
                    <th>Fecha y hora</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estados as $estado)
                <tr>
                    <th><input type="checkbox" name="estados[]" id="{{$estado->id}}" value="{{$estado->id}}"></th>
                    <td>{{$estado->name}}</td>
                    <td>{{$estado->pivot->observacion}}</td>
                    <td>{{\App\User::find($estado->pivot->funcionario_id)->first()->name}}</td>
                    <td>{{$estado->pivot->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="dropdown">
            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Para todas las seleccionadas
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); generarDevolucion({{$id}});">Generar devolución</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('js/tramites/solicitudes/listadoServiciosEstados.js')}}"></script>