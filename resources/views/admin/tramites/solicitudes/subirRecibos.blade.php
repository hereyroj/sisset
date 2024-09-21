<form enctype="multipart/form-data" style="overflow-x:hidden">
    <input type="hidden" name="tramite_servicio" value="{{$id}}">
    <section>
        <h4>Información del CUPL</h4>
        <div class="form-group">
            <label class="control-label">Número</label>
            <input type="text" class="form-control" name="numero_cupl" id="numero_cupl" required>
        </div>
        <div class="form-group">
            <label class="control-label">Repetir</label>
            <input type="text" class="form-control" name="numero_cupl_2" id="numero_cupl_2" data-parsley-equalto="#numero_cupl" required>
        </div>
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="cupl" name="cupl">
            <label class="custom-file-label" for="cupl">Documento</label>
        </div>
    </section>
    <section>
        <h4>Información de WEBSERVICES</h4>
        <div class="form-group">
            <label class="control-label">Número</label>
            <input type="text" class="form-control" name="numero_sintrat" id="numero_sintrat" required>
        </div>
        <div class="form-group">
            <label class="control-label">Repetir</label>
            <input type="text" class="form-control" name="numero_sintrat_2" id="numero_sintrat_2" data-parsley-equalto="#numero_sintrat" required>
        </div>
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="webservices" name="webservices">
            <label class="custom-file-label" for="webservices">Documento</label>
        </div>
    </section>
    <section>
        <h4>Información de la consignación</h4>
        <div class="form-group">
            <label class="control-label">Número</label>
            <input type="text" class="form-control" name="numero_consignacion" id="numero_consignacion" required>
        </div>
        <div class="form-group">
            <label class="control-label">Repetir</label>
            <input type="text" class="form-control" name="numero_consignacion_2" id="numero_consignacion_2" data-parsley-equalto="#numero_consignacion" required>
        </div>
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="consignacion" name="consignacion">
            <label class="custom-file-label" for="consignacion">Documento</label>
        </div>
    </section> 
    <div class="form-group">
        <label class="control-label" for="observacion">Observación</label>
        <textarea class="form-control" name="observacion"></textarea>
    </div>
</form>