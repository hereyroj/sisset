Echo.private('App.PreAsignaciones')
    .listen('App\\Events\\nuevaSolicitudPreAsignacion', (data) => {
        obtenerSolicitudes();
    });