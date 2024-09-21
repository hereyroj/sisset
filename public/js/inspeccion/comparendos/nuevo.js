$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    formatSubmit: 'yyyy-mm-dd',
    container: '.jconfirm-light'
});

$('.timepicker').pickatime({
    format: 'HH:i',
    formatSubmit: 'HH:i',
    interval: 10,
    container: '.jconfirm-light'
});

$(document).ready(function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/consultas/obtenerCiudadesDpto/' + $('#conductor_dpto').val(),
        success: function (data) {
            $('#conductor_ciudad').empty();
            if (data != undefined || data != null) {
                $.each(data, function (key, val) {
                    $('#conductor_ciudad').append('<option value=' + key + '>' + val + '</option>');
                })
            }
        }
    });

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/comparendos/obtenerInfracciones/' + $('#comparendo_tipo').val(),
        success: function (data) {
            $('#comparendo_infraccion').empty();
            if (data != undefined || data != null) {
                $.each(data, function (key, val) {
                    $('#comparendo_infraccion').append('<option value=' + key + '>' + val + '</option>');
                })
            }
            obtenerDescripcionInfraccion();
        }
    });
});

$('#conductor_dpto').on('change', function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/consultas/obtenerCiudadesDpto/' + this.value,
        dataType: "json",
        success: function (data) {
            $('#conductor_ciudad').empty();
            if (data != undefined || data != null) {
                $.each(data, function (key, val) {
                    $('#conductor_ciudad').append('<option value=' + key + '>' + val + '</option>');
                })
            }
        }
    });
});

$('#comparendo_tipo').on('change', function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/comparendos/obtenerInfracciones/' + $('#comparendo_tipo').val(),
        success: function (data) {
            $('#comparendo_infraccion').empty();
            if (data != undefined || data != null) {
                $.each(data, function (key, val) {
                    $('#comparendo_infraccion').append('<option value=' + key + '>' + val + '</option>');
                })
            }
        }
    });
});

function obtenerDescripcionInfraccion() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/comparendos/obtenerDescripcionInfraccion/' + $('#comparendo_infraccion').val(),
        success: function (data) {
            $('#descripcion_infraccion').empty().append(data);
        }
    });
}

$('#comparendo_infraccion').on('change', function () {
    obtenerDescripcionInfraccion();
});