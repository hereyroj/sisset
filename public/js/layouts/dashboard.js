window.Laravel = {
    csrfToken: "{{ csrf_token() }}"
}

function errorVigencia(error) {
    var alert = $.alert({
        title: 'Error!',
        type: 'blue',
        typeAnimated: true,
        content: error
    });
    alert.open();
}

$(document).ready(function () {
    obtenerUltimasNotificaciones();
});

function obtenerTodasNotificaciones() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/notificaciones/obtenerTodas',
        success: function (data) {
            if (data != undefined && data != '') {
                $('#notifications').empty().html(data);
                if ($('#notifications').find('a.unread').length > 0) {
                    $('#numeroDeNotificaciones').css('background-color', '#d43f3a');
                    $('#numeroDeNotificaciones').empty().html($('#notifications').find('a.unread').length);
                } else {
                    $('#numeroDeNotificaciones').css('background-color', '#777');
                    $('#numeroDeNotificaciones').empty().html(0);
                }
            }
        }
    });
}

function obtenerUltimasNotificaciones() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/notificaciones/ultimas',
        success: function (data) {
            if (data != undefined && data != '') {
                $('#notifications').empty().html(data);
                if ($('#notifications').find('a.unread').length > 0) {
                    $('#numeroDeNotificaciones').css('background-color', '#d43f3a');
                    $('#numeroDeNotificaciones').empty().html($('#notifications').find('a.unread').length);
                } else {
                    $('#numeroDeNotificaciones').css('background-color', '#777');
                    $('#numeroDeNotificaciones').empty().html(0);
                }
            }
        }
    });
}

function marcarNotificaciones() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/notificaciones/marcarTodasLeidas',
        success: function (data) {
            obtenerUltimasNotificaciones();
        }
    });
}

$(document).ajaxError(function (event, request, settings, thrownError) {
    if (thrownError === 'Unauthorized') {
        $.alert({
            title: '¡Error!',
            type: 'red',
            typeAnimated: true,
            content: 'La sesión ha expirado.',
            buttons: {
                aceptar: {
                    'text': 'Aceptar',
                    btnClass: 'btn-red',
                    action: function () {
                        location.reload();
                    }
                }
            }
        });
    }
});