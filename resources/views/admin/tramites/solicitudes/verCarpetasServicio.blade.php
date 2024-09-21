<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Estado</th>
                <th>Hora de solicitud</th>
                <th>Autorizada por</th>
                <th>Entregada por</th>
                <th>Hora de entrega</th>
                <th>Hora de devolución</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @if($solicitudes != null) 
                    @foreach($solicitudes as $solicitudCarpeta)
                    <tr>
                        @if($solicitudCarpeta->hasCarpetaPrestada != null)
                        <td>{{$solicitudCarpeta->hasCarpetaPrestada->getEstado()}}</td>
                        <td>{{$solicitudCarpeta->hasCarpetaPrestada->created_at}}</td>
                        @if($solicitudCarpeta->hasCarpetaPrestada->hasFuncionarioAutoriza != null)
                        <td>{{$solicitudCarpeta->hasCarpetaPrestada->hasFuncionarioAutoriza->name}}</td>
                        @else
                        <td></td>
                        @endif 
                        @if($solicitudCarpeta->hasCarpetaPrestada->hasFuncionarioEntrega != null)
                        <td>{{$solicitudCarpeta->hasCarpetaPrestada->hasFuncionarioEntrega->name}}</td>
                        @else
                        <td></td>
                        @endif
                        <td>{{$solicitudCarpeta->hasCarpetaPrestada->fecha_entrega}}</td>
                        <td>{{$solicitudCarpeta->hasCarpetaPrestada->fecha_devolucion}}</td>
                        @else
                        <td>{{$solicitudCarpeta->getEstado()}}</td>
                        <td>{{$solicitudCarpeta->created_at}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @endif
                    </tr>
                    @endforeach 
                @else
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
            </tr>
        </tbody>
    </table>
</div>