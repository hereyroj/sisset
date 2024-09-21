<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Año</th>
            <th>Impide cambios</th>
            <th>Fecha inicio</th>
            <th>Fecha terminación</th>
            <th>Salario mínimo</th>
            <th>Empresa</th>
            <th>PQR</th>
            <th>Tramites</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($registros as $registro)
            <tr>
                <td>
                    {{$registro->vigencia}}
                </td>
                <td>
                    {{$registro->impedir_cambios}}
                </td>
                <td>
                    {{$registro->inicio_vigencia}}
                </td>
                <td>
                    {{$registro->final_vigencia}}
                </td>
                <td>
                    {{$registro->salario_minimo}}
                </td>
                <td>
                    @if($registro->hasEmpresa != null)
                        <button type="button" class="btn btn-secondary" onclick="verEmpresa({{$registro->id}})">Ver</button>
                    @endif
                </td>
                <td>
                    @if($registro->hasPQR != null)
                        <button type="button" class="btn btn-secondary" onclick="verPQR({{$registro->id}})">Ver</button>
                    @endif
                </td>
                <td>
                    @if($registro->hasTramite != null)
                        <button type="button" class="btn btn-secondary" onclick="verTramite({{$registro->id}})">Ver</button>
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarRegistro({{$registro->id}})">Editar</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>