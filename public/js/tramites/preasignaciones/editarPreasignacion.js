$(document).on('change', '#clase_vehiculo', function () {
    obtenerServicios();
});

function obtenerServicios() {
    $('#servicio_vehiculo').empty();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/tramites/preasignaciones/serviciosPorClase/' + $('#clase_vehiculo').val(),
        dataType: 'json',
        success: function (data) {
            if (data != undefined && data != '' && data.length > 0) {
                $.each(data, function (key, value) {
                    $('#servicio_vehiculo').append('<option value="' + value['id'] + '">' + value['name'] + '</option>');
                });
            }
        }
    });
}