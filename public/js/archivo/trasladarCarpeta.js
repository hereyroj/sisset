$('#departamentoTraslado').on('change', function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/archivo/obtenerCiudadesDpto/' + this.value,
        dataType: "json",
        success: function (data) {
            $('#ciudadTraslado').empty();
            if (data.length > 0) {
                $.each(data, function (key, val) {
                    $('#ciudadTraslado').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
                })
            }
        }
    });
});

$(document).ready(function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/archivo/obtenerCiudadesDpto/' + $('#departamentoTraslado').val(),
        dataType: "json",
        success: function (data) {
            $('#ciudadTraslado').empty();
            if (data.length > 0) {
                $.each(data, function (key, val) {
                    $('#ciudadTraslado').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
                })
            }
        }
    });
});

$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});