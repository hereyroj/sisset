<form>
    <input type="hidden" name="idClase" value="{{$clase->id}}">
    <div class="form-group">
        <label for="nombreClase" class="control-label">Nombre</label>
        <input type="text" id="nombreClase" name="nombreClase" class="form-control" value="{{$clase->name}}">
    </div>
    <div class="form-group">
        <label for="requiereLetra" class="control-label">Requiere letra</label>
        <select id="requiereLetra" name="requiereLetra" class="form-control">
            <option value="yes" @if($clase->required_letter == 'yes') selected @endif>Si</option>
            <option value="no" @if($clase->required_letter == 'no') selected @endif>No</option>
        </select>
    </div>
    <div class="form-group">
        <label for="pre_asignable" class="control-label">Es Pre-asignable?</label>
        <select id="pre_asignable" name="pre_asignable" class="form-control">
            <option value="SI" @if($clase->pre_asignable == 'SI') selected @endif>SI</option>
            <option value="NO" @if($clase->pre_asignable == 'NO') selected @endif>NO</option>
        </select>
    </div>
    <div class="form-group" style="text-align: center;">
        <label class="control-label">Letras terminación</label><br>
        <div class="row">
            @foreach($letras_terminacion as $lt)
                <div class="col-md-1">
                    <input type="checkbox" name="lst[]" value="{{$lt->id}}" class="form-control" @if($clase->checkHasLT($lt->id)) checked @endif>
                    <label class="control-label">{{$lt->name}}</label>
                </div>
            @endforeach
        </div>
    </div>
</form>