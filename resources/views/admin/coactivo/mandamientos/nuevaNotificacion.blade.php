<form enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-group">
        <label class="control-label" for="">Consecutivo</label>
        <input type="text" class="form-control" name="consecutivo" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Tipo notificación</label>
        {{Form::select('tipo_notificacion', $tipos, null, ['class'=>'form-control', 'id'=>'tipo_notificacion'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="">Fecha notificación</label>
        <input type="text" class="form-control datepicker" name="fecha_notificacion" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Fecha max. presentación</label>
        <input type="text" class="form-control datepicker" name="fecha_max_presentacion" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Medio</label>
        {{Form::select('notificacion_medio', $medios, null, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="">Empresa transporte</label>
        {{Form::select('empresa_mensajeria', $empresas, null, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="">Número de guía</label>
        <input type="text" class="form-control" name="numero_guia">
    </div>
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="documento" name="documento">
            <label class="custom-file-label" for="documento">Documento notificación</label>
        </div>
        <h5>Previzualización</h5>
        <iframe id="viewer" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    </div>
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="pantallazo_runt" name="pantallazo_runt">
            <label class="custom-file-label" for="pantallazo_runt">Pantallazo RUNT</label>
        </div>
        <h5>Previzualización</h5>
        <iframe id="viewer2" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/nuevaNotificacion.js')}}"></script>