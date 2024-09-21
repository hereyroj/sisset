<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Vigencia</th>
            <th>Marca</th>
            <th>Línea</th>
            <th>Modelo</th>
            <th>Avalúo</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($basesGravables as $baseGravable)
            <tr>
                <td>{{$baseGravable->vigencia}}</td>
                <td>{{$baseGravable->hasVehiculoLinea->hasMarca->name}}</td>
                <td>{{$baseGravable->hasVehiculoLinea->nombre}}</td>
                <td>{{$baseGravable->modelo}}</td>
                <td>{{$baseGravable->avaluo}}</td>
                <td><button type="button" class="btn btn-secondary btn-block" onclick="editarBaseGravable({{$baseGravable->id}});">Editar</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$basesGravables->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>