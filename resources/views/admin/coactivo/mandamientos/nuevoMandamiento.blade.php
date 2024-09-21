<form>
    <div class="form-group">
        <label class="control-label" for="">Tipo proceso</label>
        <select class="form-control" name="tipo_proceso" required>
            <option value="1">Comparendo</option>
            <option value="2">Acuerdo de pago</option>
        </select>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Número proceso</label>
        <input type="text" class="form-control" name="numero_proceso" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Consecutivo mandamiento</label>
        <input type="text" class="form-control" name="consecutivo_mandamiento" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Fecha mandamiento</label>
        <input type="text" class="form-control datepicker" name="fecha_mandamiento" required>
    </div>    
    <div class="form-group">Valor</label>
        <input type="text" class="form-control" name="valor" required>
    </div> 
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="documento_mandamiento" name="documento_mandamiento" id="documento_mandamiento">
            <label class="custom-file-label" for="documento_mandamiento">Documento mandamiento</label>
        </div>
        <h5>Previzualización</h5>
        <iframe id="viewerMandamiento" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/nuevoMandamiento.js')}}"></script>