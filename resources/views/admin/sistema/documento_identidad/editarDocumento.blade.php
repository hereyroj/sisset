<form>
    <input type="hidden" id="id" name="id" value="{{$documento->id}}">
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" id="name" class="form-control" value="{{$documento->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="requiere_numero">Requiere número?</label>
        {{Form::select('requiere_numero', ['SI'=>'SI', 'NO'=>'NO'], $documento->requiere_numero, ['class'=>'form-control'])}}
    </div>
</form>