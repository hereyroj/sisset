Echo.private('App.PQR')
    .listen('App\\Events\\nuevoCoEx', (data) => {
        obtenerCoEx();
    })
    .listen('App\\Events\\nuevoCoIn', (data) => {
        obtenerCoIn()
    })
    .listen('App\\Events\\nuevoCoSa', (data) => {
        obtenerCoSa();
    });