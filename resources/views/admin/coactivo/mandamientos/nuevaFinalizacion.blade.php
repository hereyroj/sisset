<form enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-group">
        <label class="control-label" for="tipo_finalizacion">Tipo finalización</label>
        {{ Form::select('tipo_finalizacion', $tipos, null, ['class'=>'form-control', 'required'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="">Fecha finalización</label>
        <input type="text" class="form-control datepicker" id="fecha_finalizacion" name="fecha_finalizacion" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Observación</label>
        <textarea name="observacion" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="documento" name="documento">
            <label class="custom-file-label" for="documento">Documento finalización</label>
        </div>
        <h5>Previzualización</h5>
        <iframe id="viewer" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/nuevaFinalizacion.js')}}"></script>