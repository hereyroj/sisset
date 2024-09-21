<form>
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-group">
        <label for="fecha_envio">Fecha de envío<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="date" class="form-control datepicker" id="fecha_envio" name="fecha_envio" placeholder="Clic para establecer fecha" required>
    </div>
    <div class="form-group">
        <label for="hora_envio">Hora de envío<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        <input type="date" class="form-control timepicker" id="hora_envio" name="hora_envio" placeholder="Clic para establecer hora" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="modalidad_envio">Modalidad de envío<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
        {!! Form::select('modalidad_envio', $modalidadesEnvios, null, ['class' => 'form-control', 'id' => 'modalidad_envio', 'required']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="empresa_envio">Empresa de envío</label>
        {!! Form::select('empresa_envio', $empresasEnvios, null, ['class' => 'form-control', 'id' => 'empresa_envio']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero_guia">Número de guía</label>
        <input type="text" name="numero_guia" id="numero_guia" class="form-control">
    </div>
</form>
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/registrarEnvio.js')}}"></script>