Echo.private('App.Turnos')
    .listen('App\\Events\\turnoGenerado', (data) => {
        if (!window.turnoActivo) {
            $.confirm({
                title: 'Hay un nuevo turno disponible',
                content: 'Tramite: ' + data.turno.turno + '',
                autoClose: 'cancelar|5000',
                buttons: {
                    llamar: {
                        title: 'Llamar turno',
                        action: function () {
                            llamarTurno();
                        }
                    },
                    cancelar: {
                        title: 'Cancelar',
                        action: function () {

                        }
                    }
                }
            });
        }
    });