<form enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$cuota->id}}">
    <input type="hidden" name="acuerdo_id" id="acuerdo_id" value="{{$cuota->acuerdo_pago_id}}">
    <div class="form-group">
        <label class="control-label">Fecha pago</label>
        <input type="text" class="form-control datepicker" name="fecha_pago" required>
    </div>
    <div class="custom-file mb-3">
        <input type="file" class="custom-file-input" name="consignacion" id="consignacion" data-parsley-required>
        <label class="custom-file-label" for="consignacion">Consignación</label>
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="factura_sintrat" id="factura_sintrat" data-parsley-required>
        <label class="custom-file-label" for="factura_sintrat">Factura WEBSERVICES</label>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/inspeccion/acuerdos_pagos/pagarCuota.js')}}"></script>