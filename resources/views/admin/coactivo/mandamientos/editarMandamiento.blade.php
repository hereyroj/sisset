<form>
    <input type="hidden" name="id" value="{{$mandamiento->id}}">
    <div class="form-group">
        <label class="control-label" for="">Tipo proceso</label>
        <select class="form-control" name="tipo_proceso" required>
            <option value="1" @if($mandamiento->proceso_type === 'App\comparendo') selected @endif>Comparendo</option>
            <option value="2" @if($mandamiento->proceso_type === 'App\acuerdo_pago') selected @endif>Acuerdo de pago</option>
        </select>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Número proceso</label>
        <input type="text" class="form-control" name="numero_proceso" value="@if($mandamiento->proceso_type == 'App\comparendo'){{ $mandamiento->hasProceso->numero }} @else {{ $mandamiento->hasProceso->numero_acuerdo }} @endif" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Consecutivo mandamiento</label>
        <input type="text" class="form-control" name="consecutivo_mandamiento" required value="{{$mandamiento->consecutivo}}">
    </div>    
    <div class="form-group">
        <label class="control-label" for="">Fecha mandamiento</label>
        <input type="text" class="form-control datepicker" name="fecha_mandamiento" id="fecha_mandamiento" required value="{{$mandamiento->fecha_mandamiento}}">
    </div>   
    <div class="form-group">Valor</label>
        <input type="text" class="form-control" name="valor" required value="{{$mandamiento->valor}}">
    </div>  
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="documento_mandamiento" name="documento_mandamiento">
            <label class="custom-file-label" for="documento_mandamiento">Documento mandamiento</label>
        </div>
        <h5>Previzualización</h5>
        <iframe id="viewerMandamiento" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/editarMandamiento.js')}}"></script>