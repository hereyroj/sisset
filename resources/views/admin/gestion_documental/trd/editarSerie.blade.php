{!! Form::open(['id' => 'frm-editar-serie']) !!}
<input type="hidden" name="serie_id" id="serie_id" value="{{$serie->id}}"/>
<div class="form-group">
    <label for="dependencia" class="label-form">Dependencia</label>
    {!! Form::select('dependencia', $dependencias, $serie->dependencia_id, ['class'=>'form-control', 'id' => 'dependencia']) !!}
</div>
<div class="form-group">
    <label for="nombre" class="label-form">Nombre</label>
    <input type="text" name="nombre" class="form-control" value="{{$serie->name}}"/>
</div>
{!! Form::close() !!}