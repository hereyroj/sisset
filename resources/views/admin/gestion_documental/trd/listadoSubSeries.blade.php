<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th rowspan="2" colspan="1" style="text-align: center; vertical-align: middle;">Serie</th>
                <th rowspan="2" colspan="1" style="text-align: center; vertical-align: middle;">Nombre</th>
                <th colspan="2" rowspan="1" style="border-right: 2px solid #fff;border-left: 2px solid #fff;text-align: center;">Retención</th>
                <th colspan="4" rowspan="1" style="border-right: 2px solid #fff;border-left: 2px solid #fff;text-align: center;">Disposición final</th>
                <th rowspan="2" colspan="1" style="text-align: center; vertical-align: middle;">Descripción</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Acciones</th>
            </tr>
            <tr>
                <th rowspan="1" colspan="1" style="border-right: 2px solid #fff;border-left: 2px solid #fff;text-align: center;">A. de gestión</th>
                <th rowspan="1" colspan="1" style="border-right: 2px solid #fff;border-left: 2px solid #fff;text-align: center;">A. central</th>
                <th rowspan="1" colspan="1" style="border-right: 2px solid #fff;border-left: 2px solid #fff;text-align: center;">CT</th>
                <th rowspan="1" colspan="1" style="border-right: 2px solid #fff;border-left: 2px solid #fff;text-align: center;">E</th>
                <th rowspan="1" colspan="1" style="border-right: 2px solid #fff;border-left: 2px solid #fff;text-align: center;">D</th>
                <th rowspan="1" colspan="1" style="border-right: 2px solid #fff;border-left: 2px solid #fff;text-align: center;">S</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subseries as $subserie)
            <tr>
                <td>
                    {{$subserie->hasSerie->name}}
                </td>
                <td>
                    {{$subserie->name}}
                </td>
                <td>
                    @if($subserie->archivo_gestion != null) X @endif
                </td>
                <td>
                    @if($subserie->archivo_central != null) X @endif
                </td>
                <td>
                    @if($subserie->conservacion_total != null) X @endif
                </td>
                <td>
                    @if($subserie->eliminacion != null) X @endif
                </td>
                <td>
                    @if($subserie->digitalizar != null) X @endif
                </td>
                <td>
                    @if($subserie->seleccion != null) X @endif
                </td>
                <td>
                    {{$subserie->descripcion}}
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarSubSerie({{$subserie->id}});">Editar</button>
                    <button type="button" class="btn btn-secondary" onclick="eliminarSubSerie({{$subserie->id}});">Eliminar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$subseries->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>