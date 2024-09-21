$(document).ready(function () {
    obtenerServicios();
});

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
            var oldServicio = "{{old('servicio_vehiculo')}}";
            if (data != undefined && data != '' && data.length > 0) {
                $.each(data, function (key, value) {
                    if (oldServicio == value['id']) {
                        $('#servicio_vehiculo').append('<option value="' + value['id'] + '" selected>' + value['name'] + '</option>');
                    } else {
                        $('#servicio_vehiculo').append('<option value="' + value['id'] + '">' + value['name'] + '</option>');
                    }
                });
            }
        }
    });
}