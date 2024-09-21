$(document).ready(function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/consultas/obtenerCiudadesDpto/' + $('#departamento').val(),
        success: function (data) {
            $('#municipio').empty();
            if (data != undefined || data != null) {
                $.each(data, function (key, val) {
                    $('#municipio').append('<option value=' + key + '>' + val + '</option>');
                })
            }
        }
    });
});

$('#departamento').on('change', function () {
    //alert( this.value ); // or $(this).val()
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/consultas/obtenerCiudadesDpto/' + this.value,
        dataType: "json",
        success: function (data) {
            $('#municipio').empty();
            if (data != undefined || data != null) {
                $.each(data, function (key, val) {
                    $('#municipio').append('<option value=' + key + '>' + val + '</option>');
                })
            }
        }
    });
});

$('#frm-pqr').on('reset', function () {
    //alert( this.value ); // or $(this).val()
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/consultas/obtenerCiudadesDpto/' + this.value,
        dataType: "json",
        success: function (data) {
            $('#municipio').empty();
            if (data != undefined || data != null) {
                $.each(data, function (key, val) {
                    $('#municipio').append('<option value=' + key + '>' + val + '</option>');
                });
            }
        }
    });
});