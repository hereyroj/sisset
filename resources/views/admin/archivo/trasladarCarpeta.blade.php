<form id="trasladarCarpeta">
    <input type="hidden" name="carpetaTraslado" value="{{$id}}" id="carpetaTraslado">
    <div class="form-group">
        <label for="departamentoTraslado" class="label-control">Departamento</label>
        {!! Form::select('departamentoTraslado', $departamentos, null, ['class' => 'form-control', 'id' => 'departamentoTraslado']) !!}
    </div>
    <div class="form-group">
        <label for="ciudadTraslado" class="label-control">Ciudad</label>
        <select id="ciudadTraslado" name="ciudadTraslado" class="form-control" required></select>
    </div>
    <div class="form-group">
        <label for="fecha_traslado" class="control-label">Fecha traslado</label>
        <input type="date" class="form-control datepicker" id="fecha_traslado" name="fecha_traslado" placeholder="Clic para establecer fecha">
    </div>
    <div class="form-group">
        <label for="num_certificado_runt" class="control-label">Número certificado RUNT</label>
        <input type="text" class="form-control" id="num_certificado_runt" name="num_certificado_runt">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/archivo/trasladarCarpeta.js')}}">