{!! Form::open(['enctype'=>'multipart/form-data']) !!}
<input type="hidden" name="id_pqr_entrega" id="id_pqr_entrega" value="{{$id_pqr}}">
<div class="form-group">
    <label class="control-label" for="fecha_entrega">Fecha radicado<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    <input type="text" name="fecha_entrega" class="datepicker form-control">
</div>
<div class="custom-file">
    <input type="file" class="custom-file-input" id="documento_entrega" name="documento_entrega">
    <label class="custom-file-label" for="documento_entrega">Documento radicado (PDF)</label>
    <span style="color: #990000; width: 3px;height: 3px;">*</span>
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/registrarEntrega.js')}}"></script>