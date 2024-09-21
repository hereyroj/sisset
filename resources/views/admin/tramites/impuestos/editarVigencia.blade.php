<form>
    <input type="hidden" name="id" value="{{$vigencia->id}}">
    <div class="form-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        <input class="form-control" type="text" name="vigencia" required value="{{$vigencia->vigencia}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="impuesto_publico">Impuesto público (Vx1000)</label>
        <input class="form-control" type="text" name="impuesto_publico" required value="{{$vigencia->impuesto_publico}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="cantidad_meses">Cantidad meses cobro (DIAN)</label>
        <input class="form-control" type="number" name="cantidad_meses" required value="{{$vigencia->cantidad_meses_intereses}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="derechos">Derechos entidad</label>
        <input type="text" class="form-control" name="derechos" value="{{$vigencia->derechos_entidad}}" required>
    </div>
    <div class="form-group">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th colspan="2">Interes Mensual</th>
                </tr>
                <tr>
                    <th>Mes</th>
                    <th>Porcentaje</th>
                </tr>
                </thead>
                <tbody>
                @foreach($meses as $mes)
                    <tr>
                        <td>
                            {{$mes->nombre}}
                        </td>
                        <td>
                            <input type="number" class="form-control" name="mes_{{$mes->id}}" required value="{{$vigencia->getMesInteres($mes->id)}}">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>