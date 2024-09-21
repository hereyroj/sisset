<div class="table-responsive">
    <table class="table table-striped tblCoIn">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Día</th>
                <th>Laboral</th>
                <th>Fin de semana</th>
                <th>Feriado</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registros as $registro)
            <tr>
                <td>
                    {{$registro->fecha}}
                </td>
                <td>
                    {{$registro->dia}}
                </td>
                <td>
                    @if($registro->laboral == 0) NO @else SI @endif
                </td>
                <td>
                    @if($registro->fin_de_semana == 0) NO @else SI @endif
                </td>
                <td>
                    @if($registro->feriado == 0) NO @else SI @endif
                </td>
                <td>
                    {{$registro->descripcion}}
                </td>
                <td>
                    <button type="button" class="btn btn-secondary btn-block" onclick="editarRegistro({{$registro->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$registros->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>   