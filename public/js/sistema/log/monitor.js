$(document).ready(function () {
    obtenerLogsActividades();
    obtenerLogsExcepciones();
});

function obtenerLogsActividades() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/sistema/logs/obtenerLogsActividades',
        }).done(function (data) {
            $('#activityLogs').find('table').remove();
            $('#activityLogs').find('.text-center').remove();
            $('#activityLogs').append(data);
        })
        .fail(function () {

        });
}

function obtenerLogsExcepciones() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/sistema/logs/obtenerLogsExcepciones',
        }).done(function (data) {
            $('#exceptions').find('table').remove();
            $('#exceptions').find('.text-center').remove();
            $('#exceptions').append(data);
        })
        .fail(function () {

        });
}

$(document).on("click", "#exceptions .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#exceptions').find('table').remove();
            $('#exceptions').find('.text-center').remove();
            $('#exceptions').append(data);
        }
    });
});

$(document).on("click", "#activityLogs .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#activityLogs').find('table').remove();
            $('#activityLogs').find('.text-center').remove();
            $('#activityLogs').append(data);
        }
    });
});

function verCambiosActividad(id) {
    $.confirm({
        title: 'Ver cambios de la actividad',
        content: 'url:/admin/sistema/logs/verCambiosActividad/' + id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}