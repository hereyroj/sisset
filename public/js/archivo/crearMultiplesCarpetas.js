$('#claseVehiculo').change(function () {
    $('#letraTerminacion').empty();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/sistema/vehiculos/obtenerLetrasClaseVehiculo/' + $('#claseVehiculo').val(),
        dataType: 'json',
        success: function (data) {
            $.each(data, function (key, value) {
                $('#letraTerminacion').append('<option value=' + value.name + '>' + value.name + '</option>');
            });
        }
    });
});

$(document).ready(function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/sistema/vehiculos/obtenerLetrasClaseVehiculo/' + $('#claseVehiculo').val(),
        dataType: 'json',
        success: function (data) {
            $.each(data, function (key, value) {
                $('#letraTerminacion').append('<option value=' + value.name + '>' + value.name + '</option>');
            });
        }
    });
});