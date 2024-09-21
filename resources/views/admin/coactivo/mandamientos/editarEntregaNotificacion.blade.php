<form enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$entrega->id}}">
    <div class="form-group">
        <label class="control-label" for="">Fecha devolución</label>
        <input type="date" class="form-control datepicker" id="fecha_entrega" name="fecha_entrega" required value="{{$entrega->fecha_entrega}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="">Observación</label>
        <textarea class="form-control" name="observacion" required>{{$entrega->observacion}}</textarea>
    </div>
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="documento_entrega" name="documento_entrega">
            <label class="custom-file-label" for="documento_entrega">Documento entrega</label>
        </div>
        <h5>Previzualización</h5>
        <iframe id="viewer" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/editarEntregaNotificacion.js')}}"></script>