$(document).ready(function(){
    $('#txtMsg').val('');
    obtenerListadoChat();
});

function obtenerMensajes(id, origen){
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
                $('.msg_history').scrollTop($('.msg_history')[0].scrollHeight);
                var chat = $("div[chatid='" + $('#id').val() + "'][chatorigen='"+ $('#origen').val().replace('App\\', '') +"']");
                chat.find('.badge').remove();
                chat.find('p').html();
            }
        }
    });
}

$(document).on('click', '.chat_list', function(){
    $(document).find('.active_chat').removeClass('active_chat');
    $(this).addClass('active_chat');
    $(this).find('.badge').remove();
    obtenerMensajes($(this).attr('chatid'), $(this).attr('chatorigen'));
});

function obtenerMensaje(id){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/chat/obtenerMensaje/' + id,
        dataType: 'html',
        success: function (data) {
            $('.msg_history').append(data);
            $('.msg_history').scrollTop($('.msg_history')[0].scrollHeight);                    
        }
    });
}

function enviarMensaje(){
    if($('#id').val() == null || $('#origen').val() == null){
        $.alert({
            'title': '¡Error!',
            'content': 'No se ha seleccionado un chat.'
        });
    }else{
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data: $('#sendMsg').serialize(),
            url: '/admin/chat/enviarMensaje',
            dataType: 'html',
            success: function (data) {                        
                if(data != false && data != undefined){
                    var chat = $("div[chatid='" + $('#id').val() + "'][chatorigen='"+ $('#origen').val() +"']");    
                    chat.find('p').html($('#txtMsg').val());
                    $('#txtMsg').val('');
                    obtenerMensaje(data);                                                    
                }else{
                    $.alert({
                        'title': '¡Error!',
                        'content': 'No se ha podido enviar el mensaje.'
                    });
                }
            }
        });
    }            
}

function obtenerListadoChat(){
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
                        $('.inbox_chat').find('.chat_list').first().click();
                    }
                });
        }
    });
}

function enviarArchivos(){
    $.confirm({
        title: 'Enviar archivos',
        content: 'url:/admin/chat/enviarArchivos/' + $('#id').val() +'/'+ $('#origen').val(),
        columnClass: 'col-md-12',
        buttons: {
            enviar: {
                text: 'Enviar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    var self = this;
                        $.ajax({
                            url: '/admin/chat/enviarArchivos',
                            dataType: 'html',
                            method: 'post',
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: new FormData(frm[0]),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {                                                                 
                            if(response == false){
                                self.setTitle('Error');
                                self.setContent('<div class="alert alert-danger">No se ha podido enviar el/los archvio/s</div>');
                            }else{
                                obtenerMensaje(response);
                                self.close();
                            }
                        }).fail(function () {
                            self.buttons.enviar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {
                }
            }
        }
    });
}

function marcarLeido(id){
    console.log(1);
}