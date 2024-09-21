$(document).ready(function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerSubSeries/' + $('#serie').val() + '/json',
        dataType: 'json',
    }).done(function (data) {
        $.each(data, function (key, val) {
            $('#subserie').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
        });
        $('#subserie').prop('selectedIndex', $('#subserie_id').val());
    })
});

$('#serie').change(function () {
    $('#subserie').empty();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerSubSeries/' + $('#serie').val() + '/json',
        dataType: 'json',
    }).done(function (data) {
        $.each(data, function (key, val) {
            $('#subserie').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
        });
    })
});