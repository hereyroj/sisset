<form>
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-group">
        <label class="control-label" for="">Motivo devolución</label>
        {{ Form::select('motivo_devolucion', $motivos, null, ['class'=>'form-control', 'required'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="">Fecha devolución</label>
        <input type="text" class="form-control datepicker" id="fecha_devolucion" name="fecha_devolucion" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Observación</label>
        <textarea class="form-control" name="observacion" required></textarea>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/nuevaDevolucion.js')}}"></script>
        
        