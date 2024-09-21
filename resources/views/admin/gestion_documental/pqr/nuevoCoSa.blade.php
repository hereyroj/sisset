<style>
    .boton-eliminar:hover {
        cursor: pointer;
    }

    #radicados > div {
        margin-bottom: 10px;
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
{!! Form::open(['id'=>'frm-nuevo-cosa', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data', 'style'=>'padding:15px;']) !!}
<div class="form-group">
    <label class="control-label" for="funcionario">Funcionario<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    {!! Form::select('funcionario', $funcionarios, old('funcionario'), ['class' => 'form-control', 'id'=>'funcionario', 'required']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="numero_oficio">Número de oficio</label>
    <input type="text" name="numero_oficio" class="form-control" value="{{old('numero_oficio')}}">
</div>
<div class="form-group">
    <label class="control-label" for="radicados_respuesta_1">Radicados a los que responde</label>
    <div id="radicados">
        @if(old('radicados_respuesta') != null)
            <?php
                $radicados = old('radicados_respuesta');
                $limite = count($radicados);
                for($i=0;$i<$limite;$i++){
                    echo '<div><input type="text" placeholder="'.\anlutro\LaravelSettings\Facade::get('empresa-sigla').'-AÑO-100-NUMERO" name="radicados_respuesta[]" id="radicados_respuesta_'.$i.'" class="form-control" value="'.$radicados[$i].'"></div>';
                }
            ?>
        @else
            <div><input type="text" name="radicados_respuesta[]" id="radicados_respuesta_1" class="form-control" placeholder="{{ \anlutro\LaravelSettings\Facade::get('empresa-sigla') }}-AÑO-100-NUMERO"></div>
        @endif
    </div>
    <button type="button" class="btn btn-primary" onclick="addRadicado();">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Añadir otro
    </button>
</div>
<div class="form-group">
    <label class="control-label" for="pqr_clase">Clase PQR<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    {!! Form::select('pqr_clase', $clasesPQR, old('pqr_clase'), ['class' => 'form-control', 'id'=>'pqr_clase', 'required']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="medio">Medio traslado<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    {!! Form::select('medio', $mediosTraslado, old('medio'), ['class' => 'form-control', 'id' => 'medio', 'required']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="asunto">Asunto<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    <input type="text" name="asunto" class="form-control" value="{{old('asunto')}}" required>
</div>
<div class="custom-file">
    <input type="file" class="custom-file-input" id="anexos" name="anexos">
    <label class="custom-file-label" for="anexos">Cargar anexos (comprimido en ZIP. máximo 1 archivo)</label>
</div>

{!! Form::close() !!}
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/nuevoCoSa.js')}}"></script>