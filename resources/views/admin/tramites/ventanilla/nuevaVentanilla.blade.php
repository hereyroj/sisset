<form>
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" id="name" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="codigo">Código de ventanilla</label>
        <input type="text" name="codigo" id="codigo" class="form-control">
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
                </tr>
                </thead>
                <tbody>
                @foreach($grupos as $grupo)
                    <tr>
                        <td>{!! Form::select('prioridad-'.$grupo->id, ['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'], null, ['class'=>'form-control']) !!}</td>
                        <td>
                            <input type="checkbox" name="grupos[]" value="{{$grupo->id}}" class="form-control">
                        </td>
                        <td>
                            {{$grupo->name}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>