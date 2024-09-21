<form>
    @csrf
    <!--
    @if(isset($placa))
        <input type="hidden" name="placa_id" value="{{$placa}}">
    @endif
    -->
    <input type="hidden" name="servicio_id" value="{{$servicio_id}}">
    <!--<div class="form-group">
        <h4>Placa:
            @if(isset($placa))
                {{$placa}}
            @else
                No se requiere de una placa.
            @endif
        </h4>
    </div>-->
    <div class="form-group">
        <label class="control-label" for="observacion">Observación</label>
        <textarea class="form-control" name="observacion" id="observacion"></textarea>
    </div>
</form>