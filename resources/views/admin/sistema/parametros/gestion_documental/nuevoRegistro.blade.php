<form enctype="multipart/form-data">
    <div class="from-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        {{ Form::select('vigencia', $vigencias, null, ['id'=>'vigencia', 'class'=>'form-control'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="radicado_entrada">Consecutivo radicado entrada  (Debe tener la longitud final. No debe ser mayor a seis caracteres. Puede empezar por ceros)</label>
        <input type="text" name="radicado_entrada" id="radicado_entrada" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="radicado_salida">Consecutivo radicado salida  (Debe tener la longitud final. No debe ser mayor a seis caracteres. Puede empezar por ceros)</label>
        <input type="text" name="radicado_salida" id="radicado_salida" class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="sancion_consecutivo">Consecutivo sanciones (Debe tener la longitud final. No debe ser mayor a cuatro caracteres. Puede empezar por ceros)</label>
        <input type="text" name="sancion_consecutivo" id="sancion_consecutivo" class="form-control" required>
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="encabezado_documento" id="encabezado_documento" required>
        <label class="custom-file-label" for="encabezado_documento">Encabezado para los documentos (imágen)</label>
    </div>
    Previsualización<br>
    <iframe id="viewer_encabezado_documento" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="pie_documento" id="pie_documento" required>
        <label class="custom-file-label" for="pie_documento">Pie de página para los documentos (imágen)</label>
    </div>
    Previsualización<br>
    <iframe id="viewer_pie_documento" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
</form>
<script type="text/javascript" src="{{asset('js/sistema/parametros/gestion_documental/nuevoRegistro.js')}}"></script>