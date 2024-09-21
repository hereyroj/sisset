$(document).ready(function () {
    obtenerListadoChat();
});

function obtenerMensajes(id, origen) {
    $('#id').val(id);
    $('#origen').val(origen);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/chat/obtenerMensajes/' + origen + '/' + id,
        dataType: "html",
        success: function (data) {
            if (data != null && data != undefined) {
                $('.msg_history').empty().html(data);
            }
        }
    });
}

$(document).on('click', '.chat_list', function () {
    $(document).find('.active_chat').removeClass('active_chat');
    $(this).addClass('active_chat');
    obtenerMensajes($(this).attr('chatid'), $(this).attr('chatorigen'));
});

function obtenerMensaje(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/chat/obtenerMensaje/' + id,
        dataType: 'html',
        success: function (data) {
            $('.msg_history').append(data);
        }
    });
}

function enviarMensaje() {
    if ($('#id').val() == null || $('#origen').val() == null) {
        $.alert({
            'title': '¡Error!',
            'content': 'No se ha seleccionado un chat.'
        });
    } else {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: $('#sendMsg').serialize(),
            url: '/admin/chat/enviarMensaje',
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data != false && data != undefined) {
                    obtenerMensaje(data);
                } else {
                    $.alert({
                        'title': '¡Error!',
                        'content': 'No se ha podido enviar el mensaje.'
                    });
                }
            }
        });
    }
}

function obtenerListadoChat() {
    $('.inbox_chat').empty();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/chat/obtenerListadoRooms',
        dataType: "html",
        success: function (rooms) {
            if (rooms != null && rooms != undefined) {
                $('.inbox_chat').append(rooms);
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '/admin/chat/obtenerListadoUsuarios',
                dataType: "html",
                success: function (users) {
                    if (users != null && users != undefined) {
                        $('.inbox_chat').append(users);
                    }
                }
            });
        }
    });
}