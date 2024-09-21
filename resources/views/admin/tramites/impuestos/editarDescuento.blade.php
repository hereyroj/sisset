<form>
    <input type="hidden" name="id" value="{{$descuento->id}}">
    <div class="form-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        {!! Form::select('vigencia', $vigencias, $descuento->ve_li_vi_id, ['class'=>'form-control', 'required', 'id'=>'vigencia']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="concepto">Concepto</label>
        <input type="text" name="concepto" required class="form-control" value="{{$descuento->concepto}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="porcentaje">Porcentaje (%)</label>
        <input type="numeric" name="porcentaje" required class="form-control" value="{{$descuento->porcentaje}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="vigente_desde">Vigente desde</label>
        <input type="text" name="vigente_desde" id="vigente_desde" required class="form-control datepicker" value="{{$descuento->vigente_desde}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="vigente_hasta">Vigente hasta</label>
        <input type="text" name="vigente_hasta" id="vigente_hasta" required class="form-control datepicker" value="{{$descuento->vigente_hasta}}">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/impuestos/editarDescuento.js')}}"></script>