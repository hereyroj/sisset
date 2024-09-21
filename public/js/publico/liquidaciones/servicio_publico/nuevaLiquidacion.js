$(document).ready(function () {
    $.ajax({
        url: '/servicios/liquidaciones/servicioPublico/calcularValores',
        dataType: 'json',
        method: 'post',
        data: {
            vehiculoId: $('#vehiculo').val(),
            vigenciaId: $('#vigencia').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#avaluo').val(response.avaluo);
        $('#impuesto').val(response.impuesto);
        $('#valor_mora').val(response.intereses);
        $('#valor_descuentos').val(response.descuentos);
        $('#valor_total').val(response.valor_total);
        $('#valor_total,#valor_total,#valor_descuentos,#valor_mora,#impuesto,#avaluo').maskMoney({
            thousands: '.',
            decimal: '.',
            precision: 0
        });
        $('#valor_total,#valor_total,#valor_descuentos,#valor_mora,#impuesto,#avaluo').maskMoney('mask');
    }).fail(function () {

    });
});

$('#vigencia').on('change', function () {
    $.ajax({
        url: '/servicios/liquidaciones/servicioPublico/calcularValores/',
        dataType: 'json',
        method: 'post',
        data: {
            vehiculoId: $('#vehiculo').val(),
            vigenciaId: $('#vigencia').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#avaluo').val(response.avaluo);
        $('#impuesto').val(response.impuesto);
        $('#valor_mora').val(response.intereses);
        $('#valor_descuentos').val(response.descuentos);
        $('#valor_total,#valor_total,#valor_descuentos,#valor_mora,#impuesto,#avaluo').maskMoney({
            thousands: '.',
            decimal: '.',
            precision: 0
        });
        $('#valor_total,#valor_total,#valor_descuentos,#valor_mora,#impuesto,#avaluo').maskMoney('mask');
    }).fail(function () {

    });
});