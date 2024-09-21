<form>
    <input type="hidden" name="id" value="{{$devolucion->id}}">
    <div class="form-group">
        <label class="control-label" for="">Motivo devolución</label>
        {{ Form::select('motivo_devolucion', $motivos, $devolucion->ma_devolucion_motivo_id, ['class'=>'form-control', 'required'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="">Fecha devolución</label>
        <input type="date" class="form-control datepicker" id="fecha_devolucion" name="fecha_devolucion" required value="{{$devolucion->fecha_devolucion}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="">Observación</label>
        <textarea class="form-control" name="observacion" required>{{$devolucion->observacion}}</textarea>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/editarDevolucion.js')}}"></script>