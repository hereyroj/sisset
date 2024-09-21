<form>
    <h4>Rango de fechas</h4>
    <section>
        <div class="form-group">
            <label class="control-label" for="fecha_publicacion">Fecha inicio</label>
            <input type="text" class="form-control datepicker" name="fecha_inicio" id="fecha_inicio" required>
        </div>
        <div class="form-group">
            <label class="control-label" for="fecha_publicacion">Fecha fin</label>
            <input type="text" class="form-control datepicker" name="fecha_fin" id="fecha_fin" required onchange="obtenerComparendosFecha()">
        </div>
    </section>
    <h4>Parámetros</h4>    
    <div class="form-group">
        <label class="control-label" for="tipoComparendo">Tipo de comparendo</label>
        {{ Form::select('tipoComparendo',$tiposComparendos,null,['class'=>'form-control','id'=>'tipoComparendo','required'])}}
    </div>
    <div class="form-group">
        <label class="control-label" for="fecha_sancion">Fecha de sanción</label>
        <input type="text" class="form-control datepicker" name="fecha_sancion" id="fecha_sancion" required>
    </div>
    <h4>Salarios</h4>
    <div class="input-group">
        <div class="input-group-prepend">
            <div class="input-group-text form-check">
                <input type="radio" aria-label="Mayor a" name="salario" id="mayor">
            </div>
        </div>
        <input type="text" class="form-control" aria-label="Mayor a" placeholder="Mayor a" name="vmayor">
        <div class="input-group-append">
            <div class="input-group-text form-check">
                <input type="radio" aria-label="Menor a" name="salario" id="menor">
            </div>
        </div>
        <input type="text" class="form-control" aria-label="Menor a" placeholder="Menor a" name="vmenor">
    </div>
    <h4>Otros</h4>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="fecha_resolucion_letras" name="fecha_resolucion_letras">
        <label class="form-check-label" for="defaultCheck1">
            Fecha resolución en letras
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="sobrescribir_existentes" name="sobrescribir_existentes">
        <label class="form-check-label" for="defaultCheck1">
            Sobrescribir sanciones existentes
        </label>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/inspeccion/comparendos/sancionar.js')}}"></script>