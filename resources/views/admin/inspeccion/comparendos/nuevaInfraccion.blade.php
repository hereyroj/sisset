<form>
    <div class="form-group">
        <label class="control-label" for="tipoComparendo">Tipo comparendo</label>
        {{Form::select('tipoComparendo', $tiposComparendos, null, ['class'=>'form-control', 'required'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="name">Nombre</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="description">Descripción</label>
        <textarea class="form-control" name="description" required></textarea>
    </div>
    <div class="form-group">
        <label class="control-label" for="inmoviliza">¿Inmoviliza?</label>
        {{Form::select('inmoviliza', ['1'=>'Sí', '2'=>'No'], null, ['class'=>'form-control', 'required'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="smdlv">SMDLV</label>
        <input type="text" name="smdlv" class="form-control" required>
    </div>
</form>