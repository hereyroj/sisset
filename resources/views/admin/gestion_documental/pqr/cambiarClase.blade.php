<form>
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-group">
        <label class="control-label" for="clase">Clase</label>
        {!! Form::select('clase', $clases, null, ['class'=>'form-control','id'=>'clase']) !!}
    </div>
</form>