$(document).ready(function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/vehiculos/obtenerLineasJSON/' + $('#marcaVehiculo').val(),
        dataType: 'json',
    }).done(function (data) {
        $.each(data, function (key, val) {
            if (key == $('#linea').val()) {
                $('#lineaVehiculo').append('<option value="' + key + '" selected>' + val + '</option>');
            } else {
                $('#lineaVehiculo').append('<option value="' + key + '">' + val + '</option>');
            }
        })
    });
});

$('#marcaVehiculo').on('change', function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/vehiculos/obtenerLineasJSON/' + $('#marcaVehiculo').val(),
        dataType: 'json',
    }).done(function (data) {
        $('#lineaVehiculo').empty();
        $.each(data, function (key, val) {
            $('#lineaVehiculo').append('<option value="' + key + '">' + val + '</option>');
        })
    });
});