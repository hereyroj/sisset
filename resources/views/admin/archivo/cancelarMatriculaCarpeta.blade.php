<form id="cancelarCarpeta">
    <input type="hidden" name="carpetaCancelacion" value="{{$id}}" id="carpetaCancelacion">
    <div class="form-group">
        <label for="fecha_cancelacion" class="control-label">Fecha cancelación</label>
        <input type="date" class="form-control datepicker" id="fecha_cancelacion" name="fecha_cancelacion" placeholder="Clic para establecer fecha">
    </div>
    <div class="form-group">
        <label for="motivo_cancelacion" class="label-control">Motivo cancelación</label>
        {!! Form::select('motivo_cancelacion', $motivosCancelacion, null, ['class' => 'form-control', 'id' => 'revertirEstado']) !!}
    </div>
    <div class="form-group">
        <label for="nro_certificado_runt" class="control-label">Número certificado RUNT</label>
        <input type="text" class="form-control" id="nro_certificado_runt" name="nro_certificado_runt">
    </div>
    <div class="form-group">
        <label for="nombre_funcionario" class="control-label">Funcionario que autoriza</label>
        <input type="text" class="form-control" id="nombre_funcionario" name="nombre_funcionario">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/archivo/cancelarMatriculaCarpeta.js')}}">