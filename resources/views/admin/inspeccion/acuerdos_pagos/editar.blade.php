<style>
    .boton-eliminar:hover{
        cursor:pointer;
    }
</style>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$acuerdoPago->id}}">    
    <h4>Información del acuerdo</h4>
    <div class="form-group">
        <label class="control-label">Número del acuerdo</label>
        <input type="text" class="form-control" name="numero_acuerdo" required value="{{$acuerdoPago->numero_acuerdo}}">
    </div>
    <div class="form-group">
        <label class="control-label">Fecha del acuerdo</label>
        <input type="text" class="form-control datepicker" name="fecha_acuerdo" required value="{{$acuerdoPago->fecha_acuerdo}}">
    </div>
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="acuerdo" id="acuerdo" data-parsley-max-file-size="51200" data-parsley-fileextension="pdf">
            <label class="custom-file-label" for="acuerdo">Acuerdo</label>
        </div>
        Previsualización<br>
        <iframe style="margin-bottom:20px; width: 100%;" id="viewer" frameborder="0" scrolling="no" height="700"></iframe>
    </div>
    <div class="form-group">
        <label class="control-label">Valor total</label>
        <input type="text" class="form-control" name="valor_total" required value="{{$acuerdoPago->valor_total}}">
    </div>
    <div class="form-group">
        <label class="control-label">Pago inicial</label>
        <input type="text" class="form-control" name="pago_inicial" required value="{{$acuerdoPago->pago_inicial}}">
    </div>
    <div class="form-group">
        <label class="control-label">Cuotas</label>
        <input type="number" class="form-control mb-3" name="cant_cuotas" id="cant_cuotas" required min="2" value="{{$acuerdoPago->cuotas}}">
    </div>
    <h4>Deudor</h4>
    <div class="form-group">
        <label class="control-label">Tipo documento</label>
        {{Form::select('tipo_documento_deudor', $tiposDocumentos, $acuerdoPago->hasDeudor->tipo_documento_id, ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label">Número documento</label>
        <input type="text" class="form-control" name="numero_documento_deudor" required value="{{$acuerdoPago->hasDeudor->numero_documento}}">
    </div>
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input type="text" class="form-control" name="nombre_deudor" required value="{{$acuerdoPago->hasDeudor->nombre}}">
    </div>
    <div class="form-group">
        <label class="control-label">Dirección</label>
        <input type="text" class="form-control" name="direccion_deudor" required value="{{$acuerdoPago->hasDeudor->direccion}}">
    </div>
    <div class="form-group">
        <label class="control-label">Teléfono</label>
        <input type="text" class="form-control" name="telefono_deudor" required value="{{$acuerdoPago->hasDeudor->telefono}}">
    </div>
    <div class="form-group">
        <label class="control-label">Correo electrónico</label>
        <input type="text" class="form-control" name="correo_deudor" required value="{{$acuerdoPago->hasDeudor->correo_electronico}}">
    </div>
    <div class="form-group">
        <label class="control-label">Tipo de acuerdo</label>
        <select class="form-control" name="tipo_acuerdo">
            <option value="1" @if($acuerdoPago->hasComparendos->count() > 0) selected @endif>Comparendo</option>
            <option value="2" @if($acuerdoPago->hasMandamientosPagos->count() > 0) selected @endif>Mandamiento Pago</option>
        </select>
    </div>
    <h4>Procesos</h4>
    <div class="form-group">
        <div id="procesos">
            @if($acuerdoPago->hasComparendos->count() > 0)
                @foreach($acuerdoPago->hasComparendos as $proceso)
                    <div class="input-group mb-3"><input type="text" name="procesos[]" id="procesos_{{$loop->index + 1}}" class="form-control" value="{{$proceso->numero}}"><div class="input-group-append"><span class="input-group-text boton-eliminar" onclick="delProceso('procesos_{{$loop->index + 1}}');" title="Eliminar">X</span></div></div>
                @endforeach
            @else
                @foreach($acuerdoPago->hasMandamientosPagos as $proceso)
                    <div class="input-group mb-3"><input type="text" name="procesos[]" id="procesos_{{$loop->index + 1}}" class="form-control" value="{{$proceso->consecutivo}}"><div class="input-group-append"><span class="input-group-text boton-eliminar" onclick="delProceso('procesos_{{$loop->index + 1}}');" title="Eliminar">X</span></div></div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-primary" onclick="addProceso();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Añadir otro
        </button>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/inspeccion/acuerdos_pagos/editar.js')}}"></script>