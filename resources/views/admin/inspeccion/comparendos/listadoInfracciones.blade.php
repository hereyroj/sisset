<div class="cabecera-tabla">
    <div>
        <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="obtenerInfracciones();">
            <i class="fas fa-sync"></i> Actualizar
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-success btn-actualizar btn-md" onclick="nuevaInfraccion();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
        </button>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="6">INFRACCIONES</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Tipo comparendo</th>
                <th>¿Inmoviliza?</th>
                <th>SMDLV</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($infracciones as $infraccion)
            <tr>
                <td>{{$infraccion->name}}</td>
                <td>{{$infraccion->descripcion}}</td>
                <td>{{$infraccion->hasTipoComparendo->name}}</td>
                <th>
                    @if($infraccion->inmoviliza === 1)
                    SÍ
                    @else
                    NO
                    @endif
                </th>
                <td>{{$infraccion->smdlv}}</td>
                <td>
                    <button type="button" class="btn btn-secondary  btn-block" onclick="editarInfraccion({{$infraccion->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$infracciones->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>