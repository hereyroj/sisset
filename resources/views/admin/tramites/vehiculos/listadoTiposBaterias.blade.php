<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha creación</th>
                <th>Última actualización</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiposBaterias as $tipoBateria)
            <tr>
                <td>{{$tipoBateria->name}}</td>
                <td>{{$tipoBateria->created_at}}</td>
                <td>{{$tipoBateria->updated_at}}</td>
                <td>
                     <button type="button" class="btn btn-secondary btn-editar-tipoBateria" onclick="editarTipoBateria({{$tipoBateria->id}})">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>