<div class="table-responsive">
    <table class="table table-striped ">
        <thead>
            <tr>
                <th>Razón social</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empresas as $empresa)
            <tr>
                <td>{{$empresa->name}}</td>
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