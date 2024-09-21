function verTurnos(tramite_id) {
    $.confirm({
        title: 'Ver turnos',
        content: 'url:/admin/tramites/solicitudes/verTurnos/' + tramite_id,
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verTurno(id) {
    $.confirm({
        title: 'Ver turnos',
        content: 'url:/admin/tramites/solicitudes/verTurno/' + id,
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verAsignaciones(tramite_id) {
    $.confirm({
        title: 'Ver asignaciones',
        content: 'url:/admin/tramites/solicitudes/verAsignaciones/' + tramite_id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verRadicados(tramite_id) {
    $.confirm({
        title: 'Ver radicados',
        content: 'url:/admin/tramites/solicitudes/verRadicados/' + tramite_id,
        columnClass: 'col-md-8 col-md-offset-2',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verServicios(tramite_id) {
    $.confirm({
        title: 'Ver servicios',
        content: 'url:/admin/tramites/solicitudes/verServicios/' + tramite_id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verLicencias(id) {
    $.confirm({
        title: 'Ver licencias',
        content: 'url:/admin/tramites/solicitudes/verLicencias/' + id,
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}