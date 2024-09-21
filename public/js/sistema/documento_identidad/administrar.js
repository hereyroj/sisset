$(document).ready(function () {
    obtenerDocumentos();
});

function obtenerDocumentos() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/sistema/documentosIdentidad/obtenerDocumentos',
        }).done(function (data) {
            $('#documentos').find('table').remove();
            $('#documentos').find('.text-center').remove();
            $('#documentos').append(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {}
                    }
                }
            });
        });
}

function nuevoDocumento() {
    $.confirm({
        title: 'Nuevo documento',
        content: 'url:/admin/sistema/documentosIdentidad/nuevo',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/documentosIdentidad/crear',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerDocumentos();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en el proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {}
                                }
                            }
                        });
                        return false;
                    }
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function editarDocumento(id) {
    $.confirm({
        title: 'Nuevo documento',
        content: 'url:/admin/sistema/documentosIdentidad/editar/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/documentosIdentidad/actualizar',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.guardar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerDocumentos();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en el proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {}
                                }
                            }
                        });
                        return false;
                    }
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function eliminarDocumento(id) {
    $.confirm({
        icon: 'glyphicon glyphicon-warning-sign',
        title: 'Está seguro!',
        columnClass: 'medium',
        content: 'Está seguro de querer eliminar este elemento?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-primary',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: '/admin/sistema/documentosIdentidad/eliminar/' + id,
                        dataType: 'html',
                    }).done(function (data) {
                        obtenerDocumentos();
                        self.setContent(data);
                        self.buttons.eliminar.disable();
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

function activarDocumento(id) {
    $.alert({
        title: 'Activar documento',
        content: 'url:/admin/sistema/documentosIdentidad/activar/' + id,
        onContentReady: function () {
            obtenerDocumentos();
        },
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}