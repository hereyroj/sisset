{!! Form::open(['id'=>'frm-nuevo-medio', 'class'=>'form-horizontal', 'style' => 'padding:15px;']) !!}
<div class="form-group">
    <label class="label-form" for="medio_nombre">Nombre</label>
    <input type="text" name="medio_nombre" id="medio_nombre" class="form-control" required>
</div>
{!! Form::close() !!}