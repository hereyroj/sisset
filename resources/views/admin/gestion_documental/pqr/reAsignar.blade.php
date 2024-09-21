<form style="overflow: auto;">
    <input type="hidden" name="pqrid" value="{{$id}}" id="pqrid">
    <div class="form-group">
        <label for="motivo" class="label-form">Motivo</label><br>
        <textarea name="motivo" id="motivo" placeholder="Obligatorio" required class="form-control" rows="3"></textarea>
    </div>
    @foreach($dependencias as $dependencia)
        @if($dependencia->hasFuncionarios->count() > 0)
        <div class="table-responsive">
            <table class="table  table-striped" style="text-align: center;">
                <thead>
                <tr>
                    <th colspan="3">
                        {{$dependencia->name}}
                    </th>
                </tr>
                <tr>
                    <th>Selección</th>
                    <th>Responsable</th>
                    <th>Nombre</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dependencia->hasFuncionarios as $funcionario)
                    <tr>
                        <td><input type="checkbox" name="funcionarios[]" value="{{$funcionario->id}}" @if($pqr->comprobarUsuarioAsignacion($funcionario->id) == true) checked @endif></td>
                        <td><input type="radio" name="responsable" value="{{$funcionario->id}}" @if($pqr->comprobarUsuarioResponsable($funcionario->id) == true) checked @endif></td>
                        <td>{{$funcionario->name}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @endforeach
</form>
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/reAsignar.js')}}"></script>