$(document).ready(function () {
    $('#subseries').empty();
    $('#tiposdocumentos').empty();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerSubSeries/' + $('#series').val() + '/json',
        dataType: 'json',
    }).done(function (data) {
        $.each(data, function (key, val) {
            $('#subseries').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
        });
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/trd/obtenerTiposDocumentos/' + $('#subseries').val() + '/json',
            dataType: 'json',
        }).done(function (data) {
            $.each(data, function (key, val) {
                $('#tiposdocumentos').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
            });
        })
    });
});

$(document).on('change', '#series', function () {
    $('#subseries').empty();
    $('#tiposdocumentos').empty();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerSubSeries/' + $('#series').val() + '/json',
        dataType: 'json',
    }).done(function (data) {
        $.each(data, function (key, val) {
            $('#subseries').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
        });
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/trd/obtenerTiposDocumentos/' + $('#subseries').val() + '/json',
            dataType: 'json',
        }).done(function (data) {
            $.each(data, function (key, val) {
                $('#tiposdocumentos').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
            });
        })
    });
});

$(document).on('change', '#subseries', function () {
    $('#tiposdocumentos').empty();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerTiposDocumentos/' + $('#subseries').val() + '/json',
        dataType: 'json',
    }).done(function (data) {
        $.each(data, function (key, val) {
            $('#tiposdocumentos').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
        });
    });
});