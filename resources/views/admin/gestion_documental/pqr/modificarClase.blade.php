{!! Form::open(['id'=>'frm-modificar-clase', 'class'=>'form-horizontal', 'style' => 'padding:15px;']) !!}
<input type="hidden" name="clase_id_m" value="{{$clase->id}}" id="clase_id_m">
<div class="form-group">
    <label class="label-form" for="clase_nombre_m">Nombre</label>
    <input type="text" name="clase_nombre_m" id="clase_nombre_m" class="form-control" required value="{{$clase->name}}">
</div>
<div class="form-group">
    <label class="label-form" for="required_answer_m">Require respuesta</label>
    <select class="form-control" name="required_answer_m" id="required_answer_m" required>
        <option value="SI" @if($clase->required_answer == 'SI') selected @endif >SI</option>
        <option value="NO" @if($clase->required_answer == 'NO') selected @endif >NO</option>
    </select>
</div>
<div class="form-group">
    <label class="label-form" for="dia_clase_m">Clase de día</label>
    <select class="form-control" name="dia_clase_m" id="dia_clase_m" required>
        <option value="HABIL" @if($clase->dia_clase == 'HABIL') selected @endif >HABIL</option>
        <option value="CALENDARIO" @if($clase->dia_clase == 'CALENDARIO') selected @endif >CALENDARIO</option>
    </select>
</div>
<div class="form-group">
    <label class="label-form" for="dia_cantidad_m">Cantidad de días</label>
    <input type="number" id="dia_cantidad_m" name="dia_cantidad_m" min="1" required class="form-control" value="{{$clase->dia_cantidad}}">
</div>
{!! Form::close() !!}