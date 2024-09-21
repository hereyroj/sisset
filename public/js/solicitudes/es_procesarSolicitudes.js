Echo.private('App.Solicitudes')
    .listen('App\\Events\\nuevaSolicitudCarpeta', (data) => {
        solicitudesSinAprobar();
    })
    .listen('App\\Events\\solicitudCarpetaEntregada', (data) => {
        solicitudesSinDevolver();
    });