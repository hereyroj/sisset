<form id="frmEditarNotificacionAviso" enctype="multipart/form-data">
    <input type="hidden" name="notificacion_aviso_id" value="{{ $notificacionAviso->id}}"/>
    <div class="form-group">
        <label class="control-label" for="tipo_notificacion_aviso">Tipo de notificación aviso</label>
        {{Form::select('tipo_notificacion_aviso', $tiposNotificacionesAviso, $notificacionAviso->not_aviso_tipo_id, ['class'=>'form-control', 'id'=>'tipo_notificacion_aviso'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_documento">Número documento</label>
        <input type="text" class="form-control" name="numero_documento" id="numero_documento" data-parsley-required data-parsley-type="number" value="{{$notificacionAviso->numero_documento}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre_notificado">Nombre del notificado</label>
        <input type="text" class="form-control" name="nombre_notificado" id="nombre_notificado" data-parsley-required value="{{$notificacionAviso->nombre_notificado}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_proceso">Número del proceso a notificar</label>
        <input type="text" class="form-control" name="numero_proceso" id="numero_proceso" value="{{$notificacionAviso->numero_proceso}}">
    </div>
    <div class="form-group actual_documento">
        <label class="control-label">Documento de notificación</label><br>
        <a href="{{url('admin/inspeccion/notificacionesAviso/obtenerDocumento/'.$notificacionAviso->id)}}" class="btn btn-secondary">Ver</a>
        <button type="button" class="btn btn-secondary" onclick="cambiarDocumento();">Cambiar</button>
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="documento_nuevo" id="documento_nuevo">
        <label class="custom-file-label" for="documento_nuevo">Documento de notificación</label>
    </div>
    <div class="form-group">
        <label class="control-label" for="fecha_publicacion">Fecha de publicación</label>
        <input type="text" name="fecha_publicacion" id="fecha_publicacion" class="datepicker form-control" data-parsley-required value="{{$notificacionAviso->fecha_publicacion}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="fecha_desfijacion">Fecha de desfijación</label>
        <input type="text" name="fecha_desfijacion" id="fecha_desfijacion" class="datepicker form-control" data-parsley-required value="{{$notificacionAviso->fecha_desfijacion}}">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/notificaciones_aviso/editarNotificacionAviso.js')}}"></script>