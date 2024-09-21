<table class="table table-striped table-hover table-sm">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Número</th>
            <th>Fecha</th>
            <th>Objeto</th>
            <th>Archivo</th>                
        </tr>
    </thead>
    <tbody>
        @foreach ($normativas as $normativa)
        <tr>
            <td class="text-truncate">{{$normativa->hasTipo->name}}</td>
            <td class="text-truncate">{{$normativa->numero}}</td>
            <td class="text-truncate">{{$normativa->fecha_expedicion}}</td>
            <td class="text-truncate">{{$normativa->objeto}}</td>
            <td class="text-truncate"><a href="{{url('servicios/normativas/documento/'.$normativa->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="text-center">
    {{$normativas->links('vendor.pagination.bootstrap-4')}}
</div>
