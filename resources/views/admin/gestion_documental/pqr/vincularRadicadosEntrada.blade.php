<form>
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-group">
        <label class="control-label" for="radicados_respuesta_1">Radicados a los que responde</label>
        <div id="radicados">
            @if(old('radicados_respuesta') != null)
                <?php
                $radicados = old('radicados_respuesta');
                $limite = count($radicados);
                for($i=0;$i<$limite;$i++){
                    echo '<div><input type="text" name="radicados_respuesta[]" id="radicados_respuesta_'.$i.'" class="form-control" value="'.$radicados[$i].'"></div>';
                }
                ?>
            @else
                <div><input type="text" name="radicados_respuesta[]" id="radicados_respuesta_1" class="form-control"></div>
            @endif
        </div>
    </div>
    <button type="button" class="btn btn-primary" onclick="addRadicado();">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Añadir otro
    </button>
</form>
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/vincularRadicadosEntrada.js')}}"></script>