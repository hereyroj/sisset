<form enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$finalizacion->id}}"
    <div class="form-group">
        <label class="control-label" for="tipo_finalizacion">Tipo finalización</label>
        {{ Form::select('tipo_finalizacion', $tipos, $finalizacion->ma_finalizacion_tipo_id, ['class'=>'form-control', 'required'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="">Fecha finalizacion</label>
        <input type="text" class="form-control datepicker" id="fecha_finalizacion" name="fecha_finalizacion" required value="{{$finalizacion->fecha_finalizacion}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="">Observación</label>
        <textarea name="observacion" class="form-control">{{$finalizacion->observacion}}</textarea>
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
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/editarFinalizacion.js')}}"></script>