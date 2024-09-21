<link rel="stylesheet" href="{{asset('js/vendor/pickadate/themes/default.time.css')}}">
<form enctype="multipart/form-data">
    <input type="hidden" name="registro_id" value="{{$registro->id}}">
    <div class="from-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        {{ Form::select('vigencia', $vigencias, $registro->vigencia_id, ['id'=>'vigencia', 'class'=>'form-control'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="radicado_entrada">Consecutivo radicado  (Debe tener la longitud final. No debe ser mayor a seis caracteres. Puede empezar por ceros)</label>
        <input type="text" name="radicado_tramite" id="radicado_tramite" class="form-control" value="{{$registro->radicado_tramite_consecutivo}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="inicio_atencion">Hora inicio atención</label>
        <input type="date" class="form-control timepicker" id="inicio_atencion" name="inicio_atencion" placeholder="Clic para establecer hora" value="{{$registro->inicio_atencion}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="fin_atencion">Hora fin atención</label>
        <input type="date" class="form-control timepicker" id="fin_atencion" name="fin_atencion" placeholder="Clic para establecer hora" value="{{$registro->fin_atencion}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="habilita_turno_rellamado">Habilitar re-llamado de turnos?</label>
        {{ Form::select('habilita_turno_rellamado', ['SI'=>'SI', 'NO'=>'NO'], $registro->turno_rellamado, ['id'=>'habilita_turno_rellamado', 'class'=>'form-control'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="habilita_turno_preferencial">Habilitar turnos preferenciales?</label>
        {{ Form::select('habilita_turno_preferencial', ['SI'=>'SI', 'NO'=>'NO'], $registro->turno_preferencial, ['id'=>'habilita_turno_preferencial', 'class'=>'form-control'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="habilitar_turno_transferencia">Habilitar transferencia de turnos?</label>
        {{ Form::select('habilitar_turno_transferencia', ['SI'=>'SI', 'NO'=>'NO'], $registro->turno_transferencia, ['id'=>'habilitar_turno_transferencia', 'class'=>'form-control'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="tiempo_espera_turno">Tiempo de espera turno</label>
        <input type="number" name="tiempo_espera_turno" id="tiempo_espera_turno" class="form-control" value="{{$registro->turno_tiempo_espera}}">
    </div>
    <h4>Logo etiqueta</h4>
    <img src="{{asset('storage/parametros/tramites/'.$registro->turno_logo)}}" class="img-thumbnail img-fluid">
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="logo_turno" id="logo_turno">
        <label class="custom-file-label" for="logo_turno">Cambiar logo etiqueta</label>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/sistema/parametros/tramites/editarRegistro.js')}}"></script>