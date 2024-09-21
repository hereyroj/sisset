@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{!! Form::open(['id'=>'frm-nuevo-coin', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data', 'style'=>'padding:15px;']) !!}
<div class="form-group">
    <label class="control-label" for="funcionario">Funcionario<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    {!! Form::select('funcionario', $funcionarios, old('funcionario'), ['class' => 'form-control', 'id'=>'funcionario', 'required']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="numero_oficio">Número de oficio</label>
    <input type="text" name="numero_oficio" class="form-control" value="{{old('numero_oficio')}}">
</div>
<div class="form-group">
    <label class="control-label" for="responde_oficio">Oficio al que responde (Institucional)</label>
    <input type="text" name="responde_oficio" class="form-control" value="{{old('responde_oficio')}}">
</div>
<div class="form-group">
    <label class="control-label" for="pqr_clase">Clase PQR<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    {!! Form::select('pqr_clase', $clasesPQR, old('pqr_clase'), ['class' => 'form-control', 'id'=>'pqr_clase','required']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="medio">Medio traslado<span style="color: #990000; width: 3px;height: 3px;">*</span></label>
    {!! Form::select('medio', $mediosTraslado, old('medio'), ['class' => 'form-control', 'id' => 'medio','required']) !!}
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