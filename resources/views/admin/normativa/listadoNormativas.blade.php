<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Número</th>
                <th>Fecha exp.</th>
                <th>Objeto</th>
                <th>Documento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($normativas as $normativa)
            <tr>
                <td>{{$normativa->hasTipo->name}}</td>
                <td>{{$normativa->numero}}</td>
                <td>{{$normativa->fecha_expedicion}}</td>
                <td>{{$normativa->objeto}}</td>
                <td><a class="btn btn-default" href="{{url('admin/normativa/obtenerDocumento/'.$normativa->id)}}">Ver</a></td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarNormativa({{$normativa->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$normativas->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>