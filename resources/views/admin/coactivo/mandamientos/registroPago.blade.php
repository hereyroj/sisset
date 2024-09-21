<form enctype="multipart/form-data">
    <input type="hidden" name="mandamiento_id" value="{{$mandamiento_id}}">
    <div class="form-group">
        <label class="control-label">Fecha de pago</label>
        <input type="text" class="form-control datepicker" name="fecha_pago">
    </div>
    <div class="form-group">
        <label class="control-label">Valor</label>
        <input type="text" class="form-control" name="valor">
    </div>
    <div class="form-group">
        <label class="control-label">Descuento al valor</label>
        <input type="text" class="form-control" name="descuento_valor">
    </div>
    <div class="form-group">
        <label class="control-label">Intereses</label>
        <input type="text" class="form-control" name="intereses">
    </div>
    <div class="form-group">
        <label class="control-label">Descuento a los intereses</label>
        <input type="text" class="form-control" name="intereses_descuento">
    </div>
    <div class="form-group">
        <label class="control-label">Cobro adicional</label>
        <input type="text" class="form-control" name="cobro_adicional">
    </div>
    <div class="form-group">
        <label class="control-label">Número factura</label>
        <input type="text" class="form-control" name="numero_factura">
    </div>
    <div class="form-group">
        <label class="control-label">Número consignación</label>
        <input type="text" class="form-control" name="numero_consignacion">
    </div>
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="consignacion" id="consignacion" data-parsley-max-file-size="51200" data-parsley-fileextension="pdf" required>
            <label class="custom-file-label" for="consignacion">Consignación</label>
        </div>
        Previsualización<br>
        <iframe style="margin-bottom:20px; width: 100%;" id="viewer" frameborder="0" scrolling="no" height="700"></iframe>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/registroPago.js')}}"></script>