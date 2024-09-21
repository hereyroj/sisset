$(document).ready(function () {
    $.ajax({
        url: '/admin/liquidaciones/vehiculos/calcularValores/' + $('#vehiculo').val() + '/' + $('#vigencia').val(),
        dataType: 'json',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#avaluo').val(response.avaluo);
        $('#impuesto').val(response.impuesto);
        $('#valor_mora').val(response.intereses);
        $('#valor_descuentos').val(response.descuentos);
        $('#valor_total').val(response.valor_total);
        $('#derechos_entidad').val(response.derechos_entidad);
        $('#valor_total,#valor_total,#valor_descuentos,#valor_mora,#impuesto,#avaluo,#derechos_entidad').maskMoney({
            thousands: '.',
            decimal: '.',
            precision: 0
        });
        $('#valor_total,#valor_total,#valor_descuentos,#valor_mora,#impuesto,#avaluo,#derechos_entidad').maskMoney('mask');
    }).fail(function () {

    });
});

$('#vigencia').on('change', function () {
    $.ajax({
        url: '/admin/liquidaciones/vehiculos/calcularValores/' + $('#vehiculo').val() + '/' + $('#vigencia').val(),
        dataType: 'json',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#avaluo').val(response.avaluo);
        $('#impuesto').val(response.impuesto);
        $('#valor_mora').val(response.intereses);
        $('#valor_descuentos').val(response.descuentos);
        $('#valor_total').val(response.valor_total);
        $('#derechos_entidad').val(response.derechos_entidad);
        $('#valor_total,#valor_total,#valor_descuentos,#valor_mora,#impuesto,#avaluo,#derechos_entidad').maskMoney({
            thousands: '.',
            decimal: '.',
            precision: 0
        });
        $('#valor_total,#valor_total,#valor_descuentos,#valor_mora,#impuesto,#avaluo,#derechos_entidad').maskMoney('mask');
    }).fail(function () {

    });
});