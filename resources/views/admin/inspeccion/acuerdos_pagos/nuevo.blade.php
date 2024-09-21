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
    <h4>Información del acuerdo</h4>
    <div class="form-group">
        <label class="control-label">Número del acuerdo</label>
        <input type="text" class="form-control" name="numero_acuerdo" required>
    </div>
    <div class="form-group">
        <label class="control-label">Fecha del acuerdo</label>
        <input type="text" class="form-control datepicker" name="fecha_acuerdo" required>
    </div>
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="acuerdo" id="acuerdo" required data-parsley-max-file-size="51200" data-parsley-fileextension="pdf">
            <label class="custom-file-label" for="acuerdo">Acuerdo</label>
        </div>
        Previsualización<br>
        <iframe style="margin-bottom:20px; width: 100%;" id="viewer" frameborder="0" scrolling="no" height="700"></iframe>
    </div>
    <div class="form-group">
        <label class="control-label">Valor total</label>
        <input type="text" class="form-control" name="valor_total" required>
    </div>    
    <div class="form-group">
        <label class="control-label">Pago inicial</label>
        <input type="text" class="form-control" name="pago_inicial" required>
    </div>
    <h4>Cuotas</h4>
    <div class="form-group">        
        <input type="number" class="form-control mb-3" name="cant_cuotas" id="cant_cuotas" required min="2">
        <div id="cuotas" class="container-fluid">
            
        </div>
    </div>   
    <h4>Deudor</h4> 
    <div class="form-group">
        <label class="control-label">Tipo documento</label>
        {{Form::select('tipo_documento_deudor', $tiposDocumentos, old('tipo_documento_deudor'), ['class'=>'form-control'])}}
    </div>
    <div class="form-group">
        <label class="control-label">Número documento</label>
        <input type="text" class="form-control" name="numero_documento_deudor" required>
    </div>
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input type="text" class="form-control" name="nombre_deudor" required>
    </div>
    <div class="form-group">
        <label class="control-label">Dirección</label>
        <input type="text" class="form-control" name="direccion_deudor" required>
    </div>
    <div class="form-group">
        <label class="control-label">Teléfono</label>
        <input type="text" class="form-control" name="telefono_deudor" required>
    </div>
    <div class="form-group">
        <label class="control-label">Correo electrónico</label>
        <input type="text" class="form-control" name="correo_deudor" required>
    </div>
    <div class="form-group">
        <label class="control-label">Tipo de acuerdo</label>
        <select class="form-control" name="tipo_acuerdo">
            <option value="1">Comparendos</option>
            <option value="2">Mandamiento Pago</option>
        </select>
    </div>
    <div class="form-group">
        <h4>Procesos</h4>
        <div id="procesos">
            @if(old('procesos') != null)
                <?php
                $procesos = old('procesos');
                $limite = count($procesos);
                for($i=0;$i<$limite;$i++){
                    echo '<div class="mb-3"><input type="text" name="procesos[]" id="procesos_'.$i.'" class="form-control" value="'.$procesos[$i].'"></div>';
                }
                ?>
            @else
                <div class="mb-3"><input type="text" name="procesos[]" id="procesos_1" class="form-control"></div>
            @endif
        </div>
        <button type="button" class="btn btn-primary" onclick="addProceso();">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Añadir otro
        </button>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/inspeccion/acuerdos_pagos/nuevo.js')}}"></script>