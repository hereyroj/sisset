<form>
    <input type="hidden" name="ventanilla" value="{{$ventanilla->id}}">
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" id="name" class="form-control" value="{{$ventanilla->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="codigo">Código de ventanilla</label>
        <input type="text" name="codigo" id="codigo" class="form-control" value="{{$ventanilla->codigo}}">
    </div>
    <hr>
    <div class="form-group">
        <h3>Grupos</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Prioridad</th>
                    <th>Selección</th>
                    <th>Nombre</th>
                    <th>Código</th>
                </tr>
                </thead>
                <tbody>
                @foreach($grupos as $grupo)
                    @if($ventanilla->hasTramiteGrupo($grupo->id) != null)
                        <tr>
                            <td>{!! Form::select('prioridad-'.$grupo->id, ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'], $ventanilla->hasTramiteGrupo($grupo->id)->pivot->prioridad, ['class'=>'form-control']) !!}</td>
                            <td>
                                <input type="checkbox" name="grupos[]" value="{{$grupo->id}}" class="form-control" checked>
                            </td>
                            <td>
                                {{$grupo->name}}
                            </td>
                            <td>
                                {{$grupo->codigo}}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>{!! Form::select('prioridad-'.$grupo->id, ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'], null, ['class'=>'form-control']) !!}</td>
                            <td>
                                <input type="checkbox" name="grupos[]" value="{{$grupo->id}}" class="form-control">
                            </td>
                            <td>
                                {{$grupo->name}}
                            </td>
                            <td>
                                {{$grupo->codigo}}
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>