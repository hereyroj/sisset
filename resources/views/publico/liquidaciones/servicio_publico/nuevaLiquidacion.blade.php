<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<form>
    <input type="hidden" id="vehiculo" name="vehiculo" value="{{$vehiculoId}}">
    <div class="form-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        {!! Form::select('vigencia', $vigencias, null, ['class'=>'form-control', 'required', 'id'=>'vigencia']) !!}
    </div>
    <div class="form-group">
        <label class="control-label" for="avaluo">Avaluo</label>
        <div class="input-group">
            <div class="input-group-addon">$</div>
            <input type="text" class="form-control moneda" id="avaluo" disabled>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label" for="impuesto">Valor impuesto</label>
        <div class="input-group">
            <div class="input-group-addon">$</div>
            <input type="text" class="form-control moneda" id="impuesto" disabled>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label" for="valor_mora">Valor mora</label>
        <div class="input-group">
            <div class="input-group-addon">$</div>
            <input type="text" class="form-control moneda" id="valor_mora" disabled>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label" for="valor_descuentos">Valor descuentos</label>
        <div class="input-group">
            <div class="input-group-addon">$</div>
            <input type="text" class="form-control moneda" id="valor_descuentos" disabled>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label" for="valor_total">Valor total</label>
        <div class="input-group">
            <div class="input-group-addon">$</div>
            <input type="text" class="form-control moneda" id="valor_total" disabled>
        </div>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/publico/liquidaciones/servicio_publico/nuevaLiquidacion.js')}}"></script>