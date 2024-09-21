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

function nuevoTurno(tramite_id) {
    $.confirm({
        title: 'Nuevo turno',
        content: 'url:/admin/tramites/solicitudes/nuevoTurno/' + tramite_id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/solicitudes/nuevoTurno',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.crear.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerTramitesSolicitudes();
                    }).fail(function () {
                        self.buttons.crear.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function editarSolicitud(tramite_id) {
    var tmp = $.confirm({
        title: 'Editar solicitud',
        content: 'url:/admin/tramites/solicitudes/editarSolicitud/' + tramite_id,
        columnClass: 'col-md-12',
        contentLoaded: function () {
            var alert = this.$content.find('div.alert-danger');
            if (alert.length > 0) {
                this.buttons.cerrar.show();
                this.buttons.editar.hide();
            }
        },
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/solicitudes/editarSolicitud',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.editar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerTramitesSolicitudes();
                    }).fail(function () {
                        self.buttons.editar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
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