$(document).ready(function () {
    obtenerNormativas();
    obtenerTiposNormativa();
});

function obtenerNormativas() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/normativa/obtenerTodas',
            dataType: "html"
        }).done(function (data) {
            $('#listadoNormativas').find('table').remove();
            $('#listadoNormativas').append(data);
        })
        .fail(function () {
            $('#listadoNormativas').find('.alert').remove();
            $('#listadoNormativas').prepend('<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>');
        });
}

$('#listadoNormativas').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#listadoNormativas').find('table').remove();
            $('#listadoNormativas').append(data);
        }
    });
});

function nuevaNormativa() {
    $.confirm({
        title: 'Crear normativa',
        content: 'url:/admin/normativa/nueva',
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
                            url: '/admin/normativa/nueva',
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

function editarNormativa(id) {
    $.confirm({
        title: 'Editar normativa',
        content: 'url:/admin/normativa/editarNormativa/' + id,
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
                            url: '/admin/normativa/editarNormativa',
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

function filtrarNormativas() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/normativa/filtrar/' + $('#criterios').val() + '/' + $('#filtrarNormativas').val(),
            dataType: "html"
        }).done(function (data) {
            $('#listadoNormativas').find('table').remove();
            $('#listadoNormativas').append(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
            });
        });
}

function obtenerTiposNormativa() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/normativa/obtenerListadoTiposNormativa',
        dataType: 'html',
    }).done(function (data) {
        $('#tiposNormativa').empty().html(data);
    });
}

function nuevoTipoNormativa() {
    $.confirm({
        title: 'Nuevo Tipo Normativa',
        content: 'url:/admin/normativa/nuevoTipoNormativa',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/normativa/nuevoTipoNormativa',
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
                        obtenerTiposNormativa();
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

function editarTipoNormativa(id) {
    $.confirm({
        title: 'Editar Tipo Normativa',
        content: 'url:/admin/normativa/editarTipoNormativa/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/normativa/editarTipoNormativa',
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
                        obtenerTiposNormativa();
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