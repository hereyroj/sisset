<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Vigencia</th>
            <th>Nombre</th>
            <th>Logo menú</th>
            <th>Logo empresa</th>
            <th>Logo encabezado</th>
            <th>Coordenadas</th>
            <th>Nombre director</th>
            <th>Firma director</th>
            <th>Firma inspector</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($registros as $registro)
            <tr>
                <td>
                    {{$registro->hasVigencia->vigencia}}
                </td>
                <td>
                    {{$registro->empresa_nombre}}
                </td>
                <td>
                    <img src="{{asset('storage/parametros/empresa/'.$registro->empresa_logo_menu)}}" class="rounded img-fluid" style="height: 140px; margin: 0 auto;">
                </td>
                <td>
                    <img src="{{asset('storage/parametros/empresa/'.$registro->empresa_logo)}}" class="rounded img-fluid" style="height: 140px; margin: 0 auto;">
                </td>
                <td>
                    <img src="{{asset('storage/parametros/empresa/'.$registro->empresa_header)}}" class="rounded img-fluid" style="height: 140px; margin: 0 auto;">
                </td>
                <td>
                    {{$registro->empresa_map_coordinates}}
                </td>
                <td>
                    {{$registro->nombre_director}}
                </td>
                <td>
                    <img src="{{url('admin/sistema/parametros/empresa/obtenerFirma/'.$registro->id)}}" class="rounded img-fluid" style="height: 140px; margin: 0 auto;">
                </td>
                <td>
                    <img src="{{url('admin/sistema/parametros/empresa/obtenerFirmaInspector/'.$registro->id)}}" class="rounded img-fluid" style="height: 140px; margin: 0 auto;">
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarRegistro({{$registro->id}})">Editar</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>