<link rel="stylesheet" href="{{asset('js/vendor/pickadate/themes/default.time.css')}}">
<form>
    <input type="hidden" name="registro_id" value="{{$registro->id}}">
    <div class="form-group">
        <label class="control-label" for="anio">Año</label>
        <input type="text" class="form-control" name="anio" value="{{$registro->vigencia}}" readonly>
    </div>
    <div class="form-group">
        <label class="control-label" for="impedir_cambios">Impedir cambios al finalizar la vigencia?</label>
        {{ Form::select('impedir_cambios', ['SI'=>'SI', 'NO'=>'NO'], $registro->impedir_cambios, ['id'=>'impedir_cambios', 'class'=>'form-control'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="inicio_vigencia">Fecha inicio vigencia</label>
        <input type="date" class="form-control datepicker" id="inicio_vigencia" name="inicio_vigencia" placeholder="Clic para establecer fecha" value="{{$registro->inicio_vigencia}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="fin_vigencia">Fecha terminación vigencia</label>
        <input type="date" class="form-control datepicker" id="fin_vigencia" name="fin_vigencia" placeholder="Clic para establecer fecha" value="{{$registro->final_vigencia}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="vigencia_salario_minimo">Salario mínimo</label>
        <input class="form-control" name="vigencia_salario_minimo" id="vigencia_salario_minimo" type="text" value="{{ $registro->salario_minimo }}" required>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/sistema/parametros/vigencias/editarRegistro.js')}}"></script>