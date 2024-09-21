var audio = new Audio("{{ asset('music/to-the-point.mp3') }}");
Echo.private("App.User.{{Auth::user()->id}}")
    .notification((notification) => {
        if (notification['type'] === 'App\\Notifications\\ChatNewMessage') {
            if (notification['message']['sender_id'] == $('#id').val() && notification['message']['receiver_type'] == 'App\\' + $('#origen').val()) {
                obtenerMensaje(notification['message']['uuid']);
                chat.find('p').html(notification['message']['message']);
            } else {
                var chat = $("div[chatid='" + notification['message']['sender_id'] + "'][chatorigen='" + notification['message']['receiver_type'].replace('App\\', '') + "']");
                if (chat.find('.badge').length > 0) {
                    var count = chat.find('.badge').html();
                    count = parseInt(count) + 1;
                    chat.find('.badge').html(count);
                } else {
                    chat.find('.chat_counter').append('<span class="badge badge-danger">1</span>');
                }
                chat.find('p').html(notification['message']['message']);
                audio.pause();
                audio.currentTime = 0;
                audio.play();
            }
        }
    });