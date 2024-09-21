<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nit</th>
                <th>Razón social</th>
                <th>Correo electrónico</th>
                <th>Teléfono</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empresas as $empresa)
            <tr>
                <td>{{$empresa->nit}}</td>
                <td>{{$empresa->name}}</td>
                <td>{{$empresa->email}}</td>
                <td>{{$empresa->telephone}}</td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarEmpresa({{$empresa->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$empresas->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>