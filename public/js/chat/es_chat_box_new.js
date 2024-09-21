Echo.private("App.User.{{Auth::user()->id}}")
            .notification((notification) => {
                if(notification['message']['receiver_id'] == $('#id').val() && notification['message']['receiver_type'] == $('#origen').val()){
                    obtenerMensaje(data['message']['uuid']);
                }
                if (Notification) {
                    var titulo = 'Nuevo mensaje:';
                    var opciones = {
                        icon: window.location.protocol + '//' + window.location.host + '/' + data['user']['avatar'],
                        body: data['user']['name'] + ': ' + data['message']['message']
                    };
                    var n = new Notification(titulo, opciones);
                    setTimeout(function () {
                        n.close()
                    }, 5000)
                }
            });