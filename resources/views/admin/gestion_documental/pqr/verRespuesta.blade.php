<style>
    .boton-eliminar:hover {
        cursor: pointer;
    }

    #radicados > div {
        margin-bottom: 10px;
    }

    form{
        padding-bottom: 20px;
    }
</style>
{!! Form::open(['id'=>'frm-editar-respuesta-pqr', 'enctype'=>'multipart/form-data']) !!}
<input type="hidden" name="pqr_id" id="pqr_id" value="{{$pqr->id}}">
<h4>Información</h4>
<div class="form-group">
    <label for="radicado_salida" class="label_form">Radicado de salida</label>
    <input type="text" name="radicado_salida" id="radicado_salida" class="form-control" required value="{{$pqr->getRadicadoSalida->numero}}" disabled>
</div>
<div class="form-group">
    <label for="funcionario_respuesta" class="label_form">Funcionario respuesta</label>
    <input type="text" name="funcionario_respuesta" id="funcionario_respuesta" class="form-control" required value="{{$pqr->hasPeticionario->couldHaveFuncionario->name}}" disabled>
</div>
<h4>Documentación</h4>
<div class="form-group">
    <label for="numero_consecutivo" class="label_form">Número de consecutivo</label>
    <input type="text" name="numero_consecutivo" id="numero_consecutivo" class="form-control" required value="{{$pqr->numero_consecutivo}}" disabled>
</div>
<div class="form-group">
    <label class="control-label" for="radicados_respuesta_1">Radicados a los que responde</label>
    <div id="radicados">
        <?php
        $radicados = explode(',', $pqr->radicados_respuesta);
        foreach ($radicados as $radicado) {
            echo '<div><input type="text" value="' . $radicado . '" class="form-control"  disabled></div>';
        }
        ?>
    </div>
</div>
<div class="form-group er-documento">
    <label for="anexos" class="label-form">Documento de respuesta</label><br>
    <a href="/admin/pqr/respuesta/get/documento/{{$pqr->uuid}}" class="btn btn-secondary">Ver documento</a>
</div>
<div class="form-group er-anexos">
    <label for="anexos" class="label-form">Anexos</label>
    @if($pqr->anexos == null)
        Sin anexos
    @else
        <div class="botones">
            <a href="/admin/pqr/respuesta/get/anexos/{{$pqr->uuid}}" class="btn btn-secondary">Ver anexos</a>
        </div>
    @endif
</div>
<h4>Envío</h4>
@if($pqr->hasEnvio != null)
<div class="form-group">
    <label class="control-label" for="modalidad_envio">Modalidad de envío</label>
    <input type="text" class="form-control" id="modalidad_envio" name="modalidad_envio" value="{{$pqr->hasEnvio->hasModalidadEnvio->name}}"  disabled>
</div>
<div class="form-group">
    <label class="control-label" for="empresa_envio">Empresa de envío</label>
    <input type="text" class="form-control" id="empresa_envio" name="empresa_envio" @if($pqr->hasEnvio->hasEmpresaMensajeria != null)value="{{$pqr->hasEnvio->hasEmpresaMensajeria->name}}"@endif  disabled>
</div>
<div class="form-group">
    <label for="fecha_envio">Fecha de envío</label>
    <input type="date" class="form-control datepicker" id="fecha_envio" name="fecha_envio" value="{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $pqr->hasEnvio->fecha_hora_envio)->toDateString()}}"  disabled>
</div>
<div class="form-group">
    <label for="hora_envio">Hora de envío</label>
    <input type="date" class="form-control timepicker" id="hora_envio" name="hora_envio" value="{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $pqr->hasEnvio->fecha_hora_envio)->toTimeString()}}"  disabled>
</div>
<div class="form-group">
    <label class="control-label" for="numero_guia">Número de guía</label>
    <input type="text" name="numero_guia" id="numero_guia" class="form-control" value="{{$pqr->hasEnvio->numero_guia}}"  disabled>
</div>
@else
    Sin información del envío
@endif
<h4>Entrega</h4>
@if($pqr->hasEntrega != null)
<div class="form-group">
    <label class="control-label" for="fecha_entrega">Fecha entrega</label>
    <input type="date" class="form-control datepicker" id="fecha_envio" name="fecha_envio" value="{{$pqr->hasEntrega->fecha_entrega}}"  disabled>
</div>
<div class="form-group">
    <label class="control-label">Documento entrega</label>
    <div class="botones">
        <a href="{{url('admin/mis-pqr/cosa/getDoEn/'.$pqr->hasEntrega->id)}}" class="btn btn-secondary">Ver</a>
    </div>
</div>
@else
    Sin información de la entrega
@endif
{!! Form::close() !!}
@if($pqr->hasEnvio != null)
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/verRespuesta.js')}}"></script>
@endif