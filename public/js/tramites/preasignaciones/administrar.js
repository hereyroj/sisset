$(document).ready(function () {
    obtenerSolicitudes();
    obtenerMotivosRechazo();
});

function obtenerMotivosRechazo() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/preAsignaciones/obtenerMotivosRechazo',
        dataType: 'html',
        success: function (data) {
            $('#motivos_rechazo').empty().html(data);
        }
    });
}

$('#motivos_rechazo').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#motivos_rechazo').empty().html(data);
        }
    });
});

function editarMotivoRechazo(id) {
    $.confirm({
        title: 'Editar motivo rechazo',
        content: 'url:/admin/tramites/preAsignaciones/editarMotivoRechazo/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/preAsignaciones/editarMotivoRechazo',
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
                        obtenerMotivosRechazo();
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

function eliminarMotivoRechazo(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/preAsignaciones/eliminarMotivoRechazo/' + id,
        dataType: 'html',
        success: function (data) {
            obtenerMotivosRechazo();
        }
    });
}

function restaurarMotivoRechazo(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/preAsignaciones/restaurarMotivoRechazo/' + id,
        dataType: 'html',
        success: function (data) {
            obtenerMotivosRechazo();
        }
    });
}

function nuevoMotivoRechazo() {
    $.confirm({
        title: 'Crear motivo rechazo',
        content: 'url:/admin/tramites/preAsignaciones/crearMotivoRechazo',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/preAsignaciones/crearMotivoRechazo',
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
                        obtenerMotivosRechazo();
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
                action: function () {

                }
            }
        }
    });
}

function obtenerSolicitudes() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/preAsignaciones/obtenerSolicitudes',
        dataType: 'html',
        success: function (data) {
            $('#listadoSolicitudes').empty().html(data);
        }
    });
}

function preAsignar(id) {
    $.confirm({
        title: 'Pre-asignar Especie Venal',
        content: 'url:/admin/tramites/preAsignaciones/placasDisponibles/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            preasignar: {
                text: 'Pre-asignar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    var frm = this.$content.find('form');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '/admin/tramites/preAsignaciones/preAsignarPlaca',
                        data: frm.serialize(),
                        dataType: 'html',
                        success: function (data) {
                            self.buttons.preasignar.disable();
                            self.setContent(data);
                            obtenerSolicitudes();
                        }
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

function liberarSolicitud(id) {
    $.confirm({
        title: 'Liberar Placa',
        content: 'url:/admin/tramites/preAsignaciones/liberarSolicitud/' + id,
        columnClass: 'col-md-4 col-md-offset-4',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                    obtenerSolicitudes();
                }
            }
        }
    });
}

function rechazarSolicitud(id) {
    $.confirm({
        title: 'Rechazar solicitud de Pre-asignación',
        content: 'url:/admin/tramites/preAsignaciones/rechazarLaSolicitud/' + id,
        columnClass: 'col-md-4 col-md-offset-4',
        buttons: {
            rechazar: {
                text: 'Rechazar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '/admin/tramites/preAsignaciones/rechazarSolicitud',
                        data: this.$content.find('form').serialize(),
                        dataType: 'html',
                        success: function (data) {
                            self.setContent(data);
                            self.buttons.rechazar.disable();
                            obtenerSolicitudes();
                        }
                    });
                    return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {
                    obtenerSolicitudes();
                }
            }
        }
    });
}

function nuevaPreasignacion(id) {
    $.confirm({
        title: 'Nueva Pre-Asignación',
        content: 'url:/admin/tramites/preAsignaciones/nuevaPreasignacion',
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    var frm = this.$content.find('form');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '/admin/tramites/preAsignaciones/nuevaPreasignacion',
                        data: new FormData(frm[0]),
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'html',
                        success: function (data) {
                            self.setContent(data);
                            $('div.jconfirm-scrollpane').scrollTop(0);
                            obtenerSolicitudes();
                        }
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

function matricularSolicitud(id) {
    $.confirm({
        title: 'Matricular Placa',
        content: 'url:/admin/tramites/preAsignaciones/matricularSolicitud/' + id,
        columnClass: 'col-md-4 col-md-offset-4',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                    obtenerSolicitudes();
                }
            }
        }
    });
}

function subirManifiesto(id) {
    $.confirm({
        title: 'Subir manifiesto',
        content: 'url:/admin/tramites/preAsignaciones/subirManifiesto/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            subir: {
                text: 'Subir',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    var frm = this.$content.find('form');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '/admin/tramites/preAsignaciones/subirManifiesto',
                        data: new FormData(frm[0]),
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'html',
                        success: function (data) {
                            self.buttons.subir.disable();
                            self.setContent(data);
                            obtenerSolicitudes();
                        }
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

function subirFactura(id) {
    $.confirm({
        title: 'Subir factura',
        content: 'url:/admin/tramites/preAsignaciones/subirFactura/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            subir: {
                text: 'Subir',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    var frm = this.$content.find('form');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '/admin/tramites/preAsignaciones/subirFactura',
                        data: new FormData(frm[0]),
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'html',
                        success: function (data) {
                            self.buttons.subir.disable();
                            self.setContent(data);
                            obtenerSolicitudes();
                        }
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