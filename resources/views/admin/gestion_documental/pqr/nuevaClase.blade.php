{!! Form::open(['id'=>'frm-nueva-clase', 'class'=>'form-horizontal', 'style' => 'padding:15px;']) !!}
<div class="form-group">
    <label class="label-form" for="clase_nombre">Nombre</label>
    <input type="text" name="clase_nombre" id="clase_nombre" class="form-control" required>
</div>
<div class="form-group">
    <label class="label-form" for="required_answer">Requiere respuesta</label>
    <select class="form-control" name="required_answer" id="required_answer" required>
        <option value="SI">SI</option>
        <option value="NO">NO</option>
    </select>
</div>
<div class="form-group">
    <label class="label-form" for="dia_clase">Clase de día</label>
    <select class="form-control" name="dia_clase" id="dia_clase" required>
        <option value="HABIL">HABIL</option>
        <option value="CALENDARIO">CALENDARIO</option>
    </select>
</div>
<div class="form-group">
    <label class="label-form" for="dia_cantidad">Cantidad de días</label>
    <input type="number" id="dia_cantidad" name="dia_cantidad" min="1" required class="form-control">
</div>
{!! Form::close() !!}