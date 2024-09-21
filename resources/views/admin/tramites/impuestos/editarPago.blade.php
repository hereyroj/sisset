<form enctype="multipart/form-data" id="frmEditarPago">
    <input type="hidden" name="id" value="{{$pago->id}}">
    <div class="form-group">
        <label class="control-label" for="numero_consignacion">Número de la consignación</label>
        <input type="text" name="numero_consignacion" id="numero_consignacion" class="form-control" required value="{{$pago->numero_consignacion}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="valor_consignacion">Valor de la consignación</label>
        <input type="text" name="valor_consignacion" id="valor_consignacion" class="form-control" required value="{{$pago->valor_consignacion}}">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="archivo_consignacion" id="archivo_consignacion" required>
        <label class="custom-file-label" for="archivo_consignacion">Consignación escaneada (PDF)</label>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/impuestos/editarPago.js')}}"></script>