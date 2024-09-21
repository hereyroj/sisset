{!! Form::open(['id'=>'frm-responder-pqr', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
<input type="hidden" name="r_pqr_id" value="{{$id_pqr}}" id="r_pqr_id">
<input type="hidden" name="r_pqr_asignacion_id" id="r_pqr_asignacion_id" value="{{$id_asignacion}}">
<h4>Documentación</h4>
<div class="form-group">
    <div class="col-md-12">
        <label for="numero_consecutivo" class="label-form">Número de consecutivo</label>
        <input type="text" name="numero_consecutivo" id="numero_consecutivo" class="form-control" required>
    </div>
</div>
<div class="custom-file">
    <input type="file" class="custom-file-input" name="r_documento" id="r_documento" required>
    <label class="custom-file-label" for="r_documento">Documento de respuesta (Debe tener el sticker de radicado)</label>
</div>
<div class="custom-file">
    <input type="file" class="custom-file-input" name="r_anexos" id="r_anexos">
    <label class="custom-file-label" for="r_anexos">Anexos</label>
</div>
<h4>Envío</h4>
<div class="form-group">
    <div class="col-md-12">
        <label for="numero_guia" class="label-form">Número de guía</label>
        <input type="text" name="numero_guia" id="numero_guia" class="form-control">
    </div>
</div>
<div class="form-group">
    <div class="col-md-12">
        <label for="fecha_envio" class="label-form">Fecha de envío</label>
        <input type="text" name="fecha_envio" id="fecha_envio" class="datepicker form-control" placeholder="Clic para establecer fecha">
    </div>
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/responderPQR.js')}}"></script>