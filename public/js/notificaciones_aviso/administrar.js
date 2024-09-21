$(document).ready(function () {
    obtenerNormativas();
    obtenerTiposNotificacionesAviso();
});

function obtenerNormativas() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/notificacionesAviso/obtenerTodas',
            dataType: "html"
        }).done(function (data) {
            $('#notificacionesAviso').find('table').remove();
            $('#notificacionesAviso').append(data);
        })
        .fail(function () {
            $('#notificacionesAviso').find('.alert').remove();
            $('#notificacionesAviso').prepend('<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>');
        });
}

$('#notificacionesAviso').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#notificacionesAviso').find('table').remove();
            $('#notificacionesAviso').append(data);
        }
    });
});

function nuevaNotificacionAviso() {
    $.confirm({
        title: 'Crear notificación',
        content: 'url:/admin/notificacionesAviso/nueva',
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        var formData = new FormData(this.$content.find('form')[0]);
                        $.ajax({
                            url: '/admin/notificacionesAviso/nueva',
                            dataType: 'html',
                            method: 'post',
                            data: formData,
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerNormativas();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            'title': 'Error',
                            'content': 'Error en la validación del formulario'
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

function editarNotificacionAviso(id) {
    $.confirm({
        title: 'Editar notificación',
        content: 'url:/admin/notificacionesAviso/editarNotificacionAviso/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        var formData = new FormData(this.$content.find('form')[0]);
                        $.ajax({
                            url: '/admin/notificacionesAviso/editarNotificacionAviso',
                            dataType: 'html',
                            method: 'post',
                            data: formData,
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.guardar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerNormativas();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            'title': 'Error',
                            'content': 'Error en la validación del formulario'
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

function filtrarNotificacionesAviso() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/notificacionesAviso/filtrar/' + $('#criterios').val() + '/' + $('#filtrarNotificacionesAviso').val(),
            dataType: "html"
        }).done(function (data) {
            $('#notificacionesAviso').find('table').remove();
            $('#notificacionesAviso').append(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
            });
        });
}

function eliminarNotificacionAviso(id) {
    $.confirm({
        title: 'Eliminar notificación',
        content: 'url:/admin/notificacionesAviso/eliminarNotificacionAviso/' + id,
        onContentReady: function () {
            obtenerNormativas();
        },
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function obtenerTiposNotificacionesAviso() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/notificacionesAviso/obtenerListadoTiposNotificacionesAviso',
        dataType: 'html',
    }).done(function (data) {
        $('#tiposNotificacionesAviso').empty().html(data);
    });
}

function nuevoTipoNotificacionAviso() {
    $.confirm({
        title: 'Nuevo Tipo Notificación',
        content: 'url:/admin/notificacionesAviso/nuevoTipoNotificacionAviso',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/notificacionesAviso/nuevoTipoNotificacionAviso',
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
                        obtenerTiposNotificacionesAviso();
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

function editarTipoNotificacionAviso(id) {
    $.confirm({
        title: 'Editar Tipo Notificación',
        content: 'url:/admin/notificacionesAviso/editarTipoNotificacionAviso/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/notificacionesAviso/editarTipoNotificacionAviso',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.guardar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerTiposNotificacionesAviso();
                    }).fail(function () {
                        self.buttons.guardar.disable();
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