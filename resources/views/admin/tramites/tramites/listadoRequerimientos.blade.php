@foreach ($requerimientos as $requerimiento)
<tr>
    <td>{{$requerimiento->name}}</td>
    <td>{{$requerimiento->description}}</td>
    <td><button type="button" class="btn btn-secondary btn-block" title="Editar" onclick="editarRequerimiento({{$requerimiento->id}})">Editar</button>
</tr>
@endforeach