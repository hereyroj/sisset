<form>
    <input type="hidden" name="id" id="cuota_id" value="{{$cuota->id}}">
    <input type="hidden" name="acuerdo_id" id="acuerdo_id" value="{{$cuota->acuerdo_pago_id}}">
    <div class="form-group">
        <label class="control-label">Valor</label>
        <input type="text" class="form-control" name="valor" value="{{$cuota->valor}}" required>
    </div>
    <div class="form-group">
        <label class="control-label">Fecha vencimiento</label>
        <input type="text" class="form-control datepicker" name="fecha_vencimiento" id="fecha_vencimiento" value="{{$cuota->fecha_vencimiento}}" required>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/inspeccion/acuerdos_pagos/editarCuota.js')}}"></script>