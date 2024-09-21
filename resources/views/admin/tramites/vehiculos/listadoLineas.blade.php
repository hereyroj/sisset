<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Cilindraje</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lineas as $linea)
            <tr>
                <td>{{$linea->nombre}}</td>
                <th>{{$linea->hasMarca->name}}</th>
                <td>{{$linea->cilindraje}}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-secondary btn-editar-clase" onclick="editarLinea({{$linea->id}})">Editar</button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$lineas->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>