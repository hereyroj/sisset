<form enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$pago->id}}">
    <div class="form-group">
        <label class="control-label">Fecha de pago</label>
        <input type="text" class="form-control datepicker" name="fecha_pago" id="fecha_pago" value="{{$pago->fecha_pago}}">
    </div>
    <div class="form-group">
        <label class="control-label">Valor</label>
        <input type="text" class="form-control" name="valor" value="{{$pago->valor}}">
    </div>
    <div class="form-group">
        <label class="control-label">Descuento al valor</label>
        <input type="text" class="form-control" name="descuento_valor" value="{{$pago->descuento_valor}}">
    </div>
    <div class="form-group">
        <label class="control-label">Intereses</label>
        <input type="text" class="form-control" name="intereses" value="{{$pago->valor_intereses}}">
    </div>
    <div class="form-group">
        <label class="control-label">Descuento a los intereses</label>
        <input type="text" class="form-control" name="intereses_descuento" value="{{$pago->descuento_intereses}}">
    </div>
    <div class="form-group">
        <label class="control-label">Cobro adicional</label>
        <input type="text" class="form-control" name="cobro_adicional" value="{{$pago->cobro_adicional}}">
    </div>
    <div class="form-group">
        <label class="control-label">Número factura</label>
        <input type="text" class="form-control" name="numero_factura" value="{{$pago->numero_factura}}">
    </div>
    <div class="form-group">
        <label class="control-label">Número consignación</label>
        <input type="text" class="form-control" name="numero_consignacion" value="{{$pago->numero_consignacion}}">
    </div>
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="consignacion" id="consignacion" data-parsley-max-file-size="51200" data-parsley-fileextension="pdf">
            <label class="custom-file-label" for="consignacion">Consignación</label>
        </div>
        Previsualización<br>
        <iframe style="margin-bottom:20px; width: 100%;" id="viewer" frameborder="0" scrolling="no" height="700"></iframe>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/inspeccion/comparendos/editarPago.js')}}"></script>