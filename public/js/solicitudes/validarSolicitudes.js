function filtrarSinValidar() {
    if ($('#filtrarSinValidar').val() == "" || $('#criteriosSinValidar').val() == "") {
        $.alert({
            title: 'Error!',
            theme: 'light',
            type: 'red',
            content: 'No se ha especificado el parámetro de búsqueda correctamente.'
        });
    } else {
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '/admin/solicitudes/administracion/filtro/sinValidar/' + $('#filtrarSinValidar').val() + '/' + $('#criteriosSinValidar').val(),
                dataType: "html"
            }).done(function (data) {
                $('#validar_solicitudes').find('table').remove();
                $('#validar_solicitudes').find('.text-center').remove();
                $('#validar_solicitudes').append(data);
            })
            .fail(function () {
                $.alert({
                    title: 'Error!',
                    theme: 'light',
                    type: 'red',
                    content: 'No se ha podido establecer una conexión con el servidor.'
                });
            });
    }
}

$(document).ready(function () {
    solicitudesSinValidar();
    obtenerTiposValidaciones();
});

function solicitudesSinValidar() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/solicitudes/administracion/sinValidar'
        }).done(function (data) {
            $('#validar_solicitudes').find('table').remove();
            $('#validar_solicitudes').find('.text-center').remove();
            $('#validar_solicitudes').append(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                theme: 'light',
                type: 'red',
                content: 'No se ha podido establecer una conexión con el servidor.'
            });
        });
}

function obtenerTiposValidaciones() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/solicitudes/administracion/obtenerTiposValidaciones'
        }).done(function (data) {
            $('#tipos_validaciones').find('table').remove();
            $('#tipos_validaciones').html(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                theme: 'light',
                type: 'red',
                content: 'No se ha podido establecer una conexión con el servidor.'
            });
        });
}

function validarSolicitud(id) {
    $.confirm({
        title: 'Validar solicitud',
        content: 'url:/admin/solicitudes/administracion/validarSolicitud/' + id,
        buttons: {
            validar: {
                text: 'Validar',
                btnClass: 'btn-red',
                action: function () {
                    var self = this;
                    var form = this.$content.find('form');
                    $.ajax({
                        url: '/admin/solicitudes/administracion/validarSolicitud',
                        dataType: 'html',
                        method: 'post',
                        data: form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.validar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        solicitudesSinValidar();
                    }).fail(function () {
                        self.buttons.validar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
                }
            },
            cancelar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function nuevoTipoValidacion() {
    $.confirm({
        title: 'Crear tipo validación',
        content: 'url:/admin/solicitudes/administracion/crearTipoValidacion',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/solicitudes/administracion/crearTipoValidacion',
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
                        obtenerTiposValidaciones();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
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

function editarTipoValidacion(id) {
    $.confirm({
        title: 'Editar tipo validación',
        content: 'url:/admin/solicitudes/administracion/editarTipoValidacion/' + id + '',
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/solicitudes/administracion/editarTipoValidacion',
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
                        obtenerTiposValidaciones();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
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