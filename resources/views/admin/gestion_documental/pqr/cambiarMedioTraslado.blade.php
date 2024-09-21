<form>
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-group">
        <label class="control-label" for="clase">Medio</label>
        {!! Form::select('medio', $medios, null, ['class'=>'form-control','id'=>'medio']) !!}
    </div>
</form>