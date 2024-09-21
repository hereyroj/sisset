<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>
                Tipo de sustrato
            </th>
            <th>
                Número
            </th>
            <th>
                Nomenclatura RUNT
            </th>
            <th>
                Estado
            </th>
            <th>
                Acciones
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($sustratos as $sustrato)
            <tr>
                <td>
                    {{$sustrato->hasTipoSustrato->name}}
                </td>
                <td>
                    {{$sustrato->numero}}
                </td>
                <td>
                    @if($sustrato->hasTipoSustrato->name == 'LICENCIA DE CONDUCCION')
                    LC{{$sustrato->numero}}
                    @else 
                    LT{{$sustrato->numero}}
                    @endif
                </td>
                <td>
                    @if($sustrato->hasAnulacion != null)
                    <button type="button" class="btn btn-danger" onclick="verAnulacion({{$sustrato->id}})">ANULADO</button>
                    @elseif($sustrato->consumido == 'NO')
                    LIBRE
                    @elseif($sustrato->consumido == 'SI')
                    <button type="button" class="btn btn-success" onclick="verConsumo({{$sustrato->id}})">CONSUMIDO</button>            
                    @else
                    N/S 
                    @endif
                </td>
                <td>
                    @if($sustrato->hasConsumo == null && $sustrato->hasAnulacion == null && $sustrato->consumido == 'NO')
                        <button type="button" class="btn btn-secondary" onclick="editarSustrato({{$sustrato->id}});">Editar</button>
                    @endif
                    @if($sustrato->hasLiberaciones->count() > 0) <button type="button" class="btn btn-warning " onclick="verLiberaciones({{$sustrato->id}});">Liberaciones</button>@endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$sustratos->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>