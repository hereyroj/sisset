$(document).ready(function () {
    obtenerDescripcionInfraccion();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/consultas/obtenerCiudadesDpto/' + $('#conductor_dpto').val(),
        success: function (data) {
            $('#comparendo_ciudad').empty();
            $('#conductor_ciudad').empty();
            if (data != undefined || data != null) {
                $.each(data, function (key, val) {
                    var infractor = "{{$comparendo->hasInfrator}}";
                    if (infractor != undefined && infractor != null) {
                        if (key == infractor.ciudad_id) {
                            $('#conductor_ciudad').append('<option value=' + key + ' selected>' + val + '</option>');
                        } else {
                            $('#conductor_ciudad').append('<option value=' + key + '>' + val + '</option>');
                        }
                    } else {
                        $('#conductor_ciudad').append('<option value=' + key + '>' + val + '</option>');
                    }
                })
            }
        }
    });
});

$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    formatSubmit: 'yyyy-mm-dd',
    container: '.jconfirm-light'
});

var fecha = $('#comparendo_fecha').val();
var $inputFecha = $('#comparendo_fecha').pickadate();
var pickerFecha = $inputFecha.pickadate('picker');
pickerFecha.set('select', fecha, {
    format: 'yyyy-mm-dd'
});

$('.timepicker').pickatime({
    format: 'HH:i',
    formatSubmit: 'H:i',
    interval: 10,
    container: '.jconfirm-light'
});

var hora = $('#comparendo_hora').val();
var $inputHora = $('#comparendo_hora').pickatime();
var pickerHora = $inputHora.pickatime('picker');
pickerHora.set('select', hora, {
    format: 'H:i A'
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

$('#comparendo_infraccion').on('change', function () {
    obtenerDescripcionInfraccion();
});