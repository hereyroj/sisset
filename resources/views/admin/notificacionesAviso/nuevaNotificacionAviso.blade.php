<form id="frnNuevaSancion" enctype="multipart/form-data">
    <div class="form-group">
        <label class="control-label" for="tipo_notificacion_aviso">Tipo de notificación aviso</label>
        {{Form::select('tipo_notificacion_aviso', $tiposNotificacionesAviso, null, ['class'=>'form-control', 'id'=>'tipo_notificacion_aviso'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_documento">Número documento</label>
        <input type="text" class="form-control" name="numero_documento" id="numero_documento" data-parsley-required data-parsley-type="number">
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre_notificado">Nombre del notificado</label>
        <input type="text" class="form-control" name="nombre_notificado" id="nombre_notificado" data-parsley-required>
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_proceso">Número del proceso a sancionar</label>
        <input type="text" class="form-control" name="numero_proceso" id="numero_proceso">
    </div>
    <div class="form-group">
        <label class="control-label" for="fecha_publicacion">Fecha de publicación</label>
        <input type="text" name="fecha_publicacion" id="fecha_publicacion" class="datepicker form-control" data-parsley-required>
    </div>
    <div class="form-group">
        <label class="control-label" for="fecha_desfijacion">Fecha de desfijación</label>
        <input type="text" name="fecha_desfijacion" id="fecha_desfijacion" class="datepicker form-control" data-parsley-required>
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="documento_notificacion_aviso" id="documento_notificacion_aviso" data-parsley-required>
        <label class="custom-file-label" for="documento_notificacion_aviso">Documento de notificación aviso</label>
    </div>
    Previsualización<br>
    <iframe id="viewer" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
</form>
<script type="text/javascript" src="{{asset('js/notificaciones_aviso/nuevaNotificacionAviso.js')}}"></script>