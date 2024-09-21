function verCarpetasServicio(servicio_id) {
    $.confirm({
        title: 'Solicitudes de Carpetas',
        content: 'url:/admin/tramites/solicitudes/verCarpetasServicio/' + servicio_id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}

function verEstadosServicio(servicio_id) {
    $.confirm({
        title: 'Estados del Servicio',
        content: 'url:/admin/tramites/solicitudes/verEstadosServicio/' + servicio_id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}

function verFinalizacionServicio(servicio_id) {
    $.confirm({
        title: 'Finalización del Servicio',
        content: 'url:/admin/tramites/solicitudes/verFinalizacionServicio/' + servicio_id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}

function verRecibosServicio(servicio_id) {
    $.confirm({
        title: 'Recibos del Servicio',
        content: 'url:/admin/tramites/solicitudes/verRecibosServicio/' + servicio_id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}