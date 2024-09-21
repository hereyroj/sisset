$(document).ready(function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/vehiculos/obtenerLineasJSON/' + $('#marcaVehiculo').val(),
        dataType: 'json',
    }).done(function (data, status, xhr) {
        $.each(data, function (key, val) {
            $('#lineaVehiculo').append('<option value="' + key + '">' + val + '</option>');
        })
    }).fail(function (xhr, status, error) {
        alert("Result: " + status + " " + error + " " + xhr.status + " " + xhr.statusText)
    });

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/consultas/obtenerCiudadesDpto/' + $('#departamento').val()
    }).done(function (data, status, xhr) {
        $('#municipio').empty();
        if (data != undefined && data != null) {
            $.each(data, function (key, val) {
                if (key == municipio) {
                    $('#municipio').append('<option value="' + key + '" selected >' + val + '</option>');
                } else {
                    $('#municipio').append('<option value="' + key + '">' + val + '</option>');
                }
            })
        }
    }).fail(function (xhr, status, error) {
        alert("Result: " + status + " " + error + " " + xhr.status + " " + xhr.statusText)
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
    }).done(function (data, status, xhr) {
        $('#lineaVehiculo').empty();
        $.each(data, function (key, val) {
            $('#lineaVehiculo').append('<option value="' + key + '">' + val + '</option>');
        })
    }).fail(function (xhr, status, error) {
        alert("Result: " + status + " " + error + " " + xhr.status + " " + xhr.statusText)
    });
});

$('#departamento').on('change', function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/consultas/obtenerCiudadesDpto/' + this.value
    }).done(function (data, status, xhr) {
        $('#municipio').empty();
        if (data != undefined && data != null) {
            $.each(data, function (key, val) {
                $('#municipio').append('<option value=' + key + '>' + val + '</option>');
            })
        }
    }).fail(function (xhr, status, error) {
        alert("Result: " + status + " " + error + " " + xhr.status + " " + xhr.statusText)
    });
});