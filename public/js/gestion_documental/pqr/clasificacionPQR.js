$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "GET",
    url: '/admin/trd/obtenerSubSeries/' + $('#m_series').val() + '/json',
    dataType: 'json',
}).done(function (subseries) {
    $.each(subseries, function (key, val) {
        $('#m_subseries').append('<option value=' + subseries[key].id + '>' + subseries[key].name + '</option>');
    });

    $('#m_subseries').val("{{$clasificacion->getDocumentoTipo->hasSubSerie->id}}");

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerTiposDocumentos/' + $('#m_subseries').val() + '/json',
        dataType: 'json',
    }).done(function (tipos) {
        $.each(tipos, function (key, val) {
            $('#m_tiposdocumentos').append('<option value=' + tipos[key].id + '>' + tipos[key].name + '</option>');
        });
        $('#m_tiposdocumentos').val("{{$clasificacion->getDocumentoTipo->id}}");
    });
});

$(document).on('change', '#m_subseries', function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerTiposDocumentos/' + $('#m_subseries').val() + '/json',
        dataType: 'json',
    }).done(function (data) {
        $('#m_tiposdocumentos').empty();

        $.each(data, function (key, val) {
            $('#m_tiposdocumentos').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
        });
    });
});

$(document).on('change', '#m_series', function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerSubSeries/' + $('#m_series').val() + '/json',
        dataType: 'json',
    }).done(function (data) {
        $('#m_tiposdocumentos').empty();
        $('#m_subseries').empty();

        $.each(data, function (key, val) {
            $('#m_subseries').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
        });
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/trd/obtenerTiposDocumentos/' + $('#m_subseries').val() + '/json',
            dataType: 'json',
        }).done(function (data) {
            $('#m_tiposdocumentos').empty();
            $.each(data, function (key, val) {
                $('#m_tiposdocumentos').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
            });
        });
    })
});