Echo.private('App.TramitesSolicitudes')
    .listen('App\\Events\\tramiteSolicitudAsignado', (data) => {
        solicitudesSinEntregar();
    });