var audio = new Audio("{{ asset('music/to-the-point.mp3')}}");

Echo.private('App.Sesiones')
    .listen('App\\Events\\UserLogOut', (data) => {
        whoIsOnline();
        Swal.fire({
            position: 'top-end',
            type: 'info',
            imageUrl: window.location.protocol + '//' + window.location.host + '/' + data['user']['avatar'],
            title: 'Un usuario ha cerrado sesión:',
            text: data['user'] + ' se ha desconectado.',
            showConfirmButton: false,
            timer: 4000
        })
    })
    .listen('App\\Events\\UserLogIn', (data) => {
        whoIsOnline();
        if (data.id != "{{auth()->user()->id}}") {
            Swal.fire({
                position: 'top-end',
                type: 'info',
                imageUrl: window.location.protocol + '//' + window.location.host + '/' + data['user']['avatar'],
                title: 'Un usuario ha iniciado sesión:',
                text: data['user'] + ' se ha conectado.',
                showConfirmButton: false,
                timer: 4000
            })
        }
    });

Echo.private('App.User.{{Auth::user()->id}}')
    .listen('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (data) => {
        if (data['type'] === 'App\\Notifications\\ChatNewMessage') {
            audio.pause();
            audio.currentTime = 0;
            audio.play();
            var count = $('#mensajesSinLeer').html();
            count = parseInt(count) + 1;
            $('#mensajesSinLeer').html(count);
            $('#mensajesSinLeer').removeClass('badge-light');
            $('#mensajesSinLeer').addClass('badge-danger');
        } else {
            obtenerUltimasNotificaciones();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '/admin/notificaciones/obtener/' + data.id,
                dataType: 'json',
            }).done(function (notificacion) {
                Swal.fire({
                    position: 'top-end',
                    type: 'warning',
                    title: notificacion.title,
                    text: notificacion.description,
                    confirmButtonText: 'Cool'
                })
            });
        }
    });