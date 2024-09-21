Echo.channel('turnos')
    .listen('App\\Events\\turnoAsignado', (data) => {
        turnos.push(data);
    });