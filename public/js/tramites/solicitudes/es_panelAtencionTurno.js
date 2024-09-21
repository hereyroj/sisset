Echo.private('App.Solicitudes')
    .listen('App\\Events\\solicitudCarpetaAprobada', (data) => {
        obtenerServiciosSolicitud();
    })
    .listen('App\\Events\\solicitudCarpetaEntregada', (data) => {
        obtenerServiciosSolicitud();
    })
    .listen('App\\Events\\solicitudCarpetaIngresa', (data) => {
        obtenerServiciosSolicitud();
    })
    .listen('App\\Events\\solicitudCarpetaDenegada', (data) => {
        obtenerServiciosSolicitud();
    })
    .listen('App\\Events\\solicitudCarpetaValidada', (data) => {
        obtenerServiciosSolicitud();
    })
    .listen('App\\Events\\TramiteFinalizado', (data) => {
        obtenerServiciosSolicitud();
    });