$(document).ready(function () {
    obtenerDependencias();
});

function obtenerDependencias() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/sistema/dependencias/obtenerDependencias',
        }).done(function (data) {
            $('#dependencias').find('table').remove();
            $('#dependencias').find('.text-center').remove();
            $('#dependencias').append(data);
        })
        .fail(function () {

        });
}

function editarDependencia(id) {
    $.confirm({
        title: 'Editar tramite',
        content: 'url:/admin/sistema/dependencias/editarDependencia/' + id,
        buttons: {
            actualizar: {
                text: 'Actualizar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/dependencias/actualizarDependencia',
                            dataType: 'html',
                            method: 'post',
                            data: this.$content.find('form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.actualizar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerDependencias();
                        }).fail(function () {
                            self.buttons.actualizar.disable();
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

$(document).on("click", "#dependencias .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#dependencias').find('table').remove();
            $('#dependencias').find('.text-center').remove();
            $('#dependencias').append(data);
        }
    });
});

function nuevaDependencia() {
    $.confirm({
        title: 'Nueva dependencia',
        content: 'url:/admin/sistema/dependencias/nuevaDependencia',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/dependencias/crearDependencia',
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
                            obtenerDependencias();
                        }).fail(function () {
                            self.buttons.actualizar.disable();
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

function eliminarDependencia(id) {
    $.confirm({
        title: 'Eliminar dependencia',
        content: 'Está seguro de eliminar esta dependencia?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-red',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/sistema/dependencias/eliminarDependencia/' + id,
                        dataType: 'html',
                        method: 'get',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.eliminar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerDependencias();
                    }).fail(function () {
                        self.buttons.actualizar.disable();
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

function activarDependencia(id) {
    $.confirm({
        title: 'Restaurar dependencia',
        content: 'Está seguro de restaurar esta dependencia?',
        buttons: {
            restaurar: {
                text: 'Restaurar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/sistema/dependencias/restaurarDependencia/' + id,
                        dataType: 'html',
                        method: 'get',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.restaurar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerDependencias();
                    }).fail(function () {
                        self.buttons.actualizar.disable();
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