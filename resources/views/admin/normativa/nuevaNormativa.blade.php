<form>
    <div class="form-group">
        <label class="control-label" for="tipo">Tipo</label>
        {!! Form::select('tipo', $tiposNormativa, null, ['id'=>'tipo', 'required', 'class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="numero">Número</label>
        <input class="form-control" type="text" name="numero" id="numero" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Fecha</label>
        <input class="form-control datepicker" type="text" name="fecha" id="fecha" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="objeto">Objeto</label>
        <textarea class="form-control" name="objeto" id="objeto" required></textarea>
    </div>    
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="documento" id="documento" data-parsley-required>
        <label class="custom-file-label" for="documento">Documento </label>
    </div>
    Previsualización<br>
    <iframe id="viewer" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
</form>
<script type="text/javascript" src="{{asset('js/normativa/nuevaNormativa.js')}}"></script>