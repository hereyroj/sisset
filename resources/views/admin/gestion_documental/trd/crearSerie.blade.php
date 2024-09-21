{!! Form::open(['id' => 'frm-crear-serie']) !!}
<div class="form-group">
    <label for="dependencia" class="label-form">Dependencia</label>
    {!! Form::select('dependencia', $dependencias, null, ['class'=>'form-control', 'id' => 'dependencia']) !!}
</div>
<div class="form-group">
    <label for="nombre" class="label-form">Nombre</label>
    <input type="text" name="nombre" class="form-control">
</div>
{!! Form::close() !!}