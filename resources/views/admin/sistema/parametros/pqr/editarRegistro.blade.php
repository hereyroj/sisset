<form enctype="multipart/form-data">
    <input type="hidden" name="registro_id" value="{{$registro->id}}">
    <div class="from-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        {{ Form::select('vigencia', $vigencias, $registro->vigencia_id, ['id'=>'vigencia', 'class'=>'form-control'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="editar_resuelto">Permitir editar procesos resueltos?</label>
        {{ Form::select('editar_resuelto', ['SI'=>'SI', 'NO'=>'NO'], $registro->editar_pqr_resuelto, ['id'=>'editar_resuelto', 'class'=>'form-control'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="previo_aviso">Días ciclo comprobación</label>
        <input type="number" name="previo_aviso" id="previo_aviso" class="form-control" value="{{$registro->dias_previo_aviso}}">
    </div>
    <h4>Logo etiqueta radicado</h4>
    <img src="{{asset('storage/parametros/pqr/'.$registro->logo_pqr_radicado)}}" class="img-thumbnail">
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="logo_radicado" id="logo_radicado">
        <label class="custom-file-label" for="logo_radicado">Cambiar logo etiqueta radicado</label>
    </div>
</form>