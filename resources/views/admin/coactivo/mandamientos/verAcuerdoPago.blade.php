<div class="row">
    <div class="col-md-6">
        <h4>Información del acuerdo de pago</h4>
        <p><strong>Fecha:</strong><br>{{$acuerdoPago->fecha_acuerdo}}</p>
        <p><strong>Número:</strong><br>{{$acuerdoPago->numero_acuerdo}}</p>
        <p><strong>Valor:</strong><br>{{$acuerdoPago->valor_total}}</p>
        <p><strong>Pago inicial:</strong><br>{{$acuerdoPago->pago_inicial}}</p>
        <p><strong>Cuotas:</strong><br>{{$acuerdoPago->cuotas}}</p>
        <p><strong>Documento:</strong><br><a href="{{url('admin/coactivo/mandamientos/obtenerDocumentoAcuerdoPago/'.$acuerdoPago->id)}}" class="btn btn-secondary">Ver</a></p>
        <p><strong>Proceso:</strong><br><button type="button" class="btn btn-secondary" onclick="verProcesoAcuerdoPago({{$acuerdoPago->id}})">Ver</button></p>
    </div>
    <div class="col-md-6">
        <h4>Información del deudor</h4>
        <p><strong>Tipo documento:</strong><br>{{$acuerdoPago->hasDeudor->hasTipoDocumento->name}}</p>
        <p><strong>Número documento:</strong><br>{{$acuerdoPago->hasDeudor->numero_documento}}</p>
        <p><strong>Nombre:</strong><br>{{$acuerdoPago->hasDeudor->nombre}}</p>
        <p><strong>Dirección:</strong><br>{{$acuerdoPago->hasDeudor->direccion}}</p>
        <p><strong>Teléfono:</strong><br>{{$acuerdoPago->hasDeudor->telefono}}</p>
        <p><strong>Correo electrónico:</strong><br>{{$acuerdoPago->hasDeudor->correo_electronico}}</p>
    </div>
</div>