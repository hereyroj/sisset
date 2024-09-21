<form>
    <div class="form-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        {!! Form::select('vigencia', $vigencias, null, ['class'=>'form-control', 'required', 'id'=>'vigencia']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="concepto">Concepto</label>
        <input type="text" name="concepto" required class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="porcentaje">Porcentaje (%)</label>
        <input type="numeric" name="porcentaje" required class="form-control">
    </div>
    <div class="form-group">
        <label class="control-label" for="vigente_desde">Vigente desde</label>
        <input type="text" name="vigente_desde" required class="form-control datepicker">
    </div>
    <div class="form-group">
        <label class="control-label" for="vigente_hasta">Vigente hasta</label>
        <input type="text" name="vigente_hasta" required class="form-control datepicker">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/impuestos/nuevoDescuento.js')}}"></script>