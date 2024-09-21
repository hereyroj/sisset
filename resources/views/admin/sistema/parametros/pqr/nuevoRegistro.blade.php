<form enctype="multipart/form-data">
    <div class="from-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        {{ Form::select('vigencia', $vigencias, null, ['id'=>'vigencia', 'class'=>'form-control'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="editar_resuelto">Permitir editar procesos resuelto?</label>
        {{ Form::select('editar_resuelto', ['SI'=>'SI', 'NO'=>'NO'], null, ['id'=>'editar_resuelto', 'class'=>'form-control'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="previo_aviso">Días ciclo comprobación</label>
        <input type="number" name="previo_aviso" id="previo_aviso" class="form-control">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="logo_radicado" id="logo_radicado">
        <label class="custom-file-label" for="logo_radicado">Logo etiqueta radicado</label>
    </div>
</form>