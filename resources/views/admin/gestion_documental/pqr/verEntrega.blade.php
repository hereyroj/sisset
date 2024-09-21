<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3>Fecha entrega</h3>
            {{$entrega->fecha_entrega}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Documento entrega</h3><br>
            <p><a href="{{url('admin/mis-pqr/cosa/getDoEn/'.$entrega->id)}}" class="btn btn-secondary">Ver</a></p>
        </div>
    </div>
</div>