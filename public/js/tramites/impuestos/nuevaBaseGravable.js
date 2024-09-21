$(document).ready(function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/vehiculos/obtenerLineasJSON/' + $('#vehiculo_marca').val(),
        dataType: 'json',
    }).done(function (data) {
        $.each(data, function (key, val) {
            $('#linea').append('<option value="' + key + '" selected >' + val + '</option>');
        })
    });
});

$('#vehiculo_marca').on('change', function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/vehiculos/obtenerLineasJSON/' + $('#vehiculo_marca').val(),
        dataType: 'json',
    }).done(function (data) {
        $('#linea').empty();
        $.each(data, function (key, val) {
            $('#linea').append('<option value="' + key + '" selected >' + val + '</option>');
        })
    });
});