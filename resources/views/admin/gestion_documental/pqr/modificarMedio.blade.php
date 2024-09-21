{!! Form::open(['id'=>'frm-modificar-medio', 'class'=>'form-horizontal', 'style' => 'padding:15px;']) !!}
<input type="hidden" name="medio_id_m" value="{{$medio->id}}" id="medio_id_m">
<div class="form-group">
    <label class="label-form" for="medio_nombre_m">Nombre</label>
    <input type="text" name="medio_nombre_m" id="medio_nombre_m" class="form-control" required value="{{$medio->name}}">
</div>
{!! Form::close() !!}