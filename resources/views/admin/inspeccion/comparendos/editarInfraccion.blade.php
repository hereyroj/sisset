<form>
    <input type="hidden" name="id" value="{{$infraccion->id}}">
    <div class="form-group">
        <label class="control-label" for="tipoComparendo">Tipo comparendo</label>
        {{Form::select('tipoComparendo', $tiposComparendos, $infraccion->comparendo_tipo_id, ['class'=>'form-control', 'required'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" class="form-control" required value="{{$infraccion->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="description">Descripción</label>
        <textarea class="form-control" name="description" required>{{$infraccion->descripcion}}</textarea>
    </div>
    <div class="form-group">
        <label class="control-label" for="inmoviliza">¿Inmoviliza?</label>
        {{Form::select('inmoviliza', ['1'=>'Sí', '2'=>'No'], $infraccion->inmoviliza, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="smdlv">SMDLV</label>
        <input type="text" name="smdlv" class="form-control" required value="{{ $infraccion->smdlv }}">
    </div>
</form>