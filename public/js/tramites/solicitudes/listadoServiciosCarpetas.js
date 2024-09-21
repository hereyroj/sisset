function obtenerCarpetasServicioModal(id) {
    $.ajax({
        url: '/admin/tramites/solicitudes/obtenerCarpetasServicio/' + id,
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#solicitudesCarpeta').empty().html(response);
    })
}

function solicitarCarpeta(id) {
    $.confirm({
        title: 'Solicitar carpeta',
        content: 'url:/admin/tramites/solicitudes/solicitarCarpeta/' + id,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                    obtenerCarpetasServicioModal(id);
                }
            }
        }
    });
}