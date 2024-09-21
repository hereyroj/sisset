$(document).ready(function () {
    obtenerRegistros();
});

function obtenerRegistros() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/sistema/parametros/to/obtenerRegistros',
        dataType: 'html',
        success: function (data) {
            $('#listadoRegistros').empty().html(data);
        }
    });
}

function nuevoRegistro() {
    $.confirm({
        title: 'Nuevo registro',
        content: 'url:/admin/sistema/parametros/to/nuevoRegistro',
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            registrar: {
                text: 'Registrar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/parametros/to/crearRegistro',
                            dataType: 'html',
                            method: 'post',
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: new FormData(frm[0]),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.registrar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerRegistros();
                        }).fail(function () {
                            self.buttons.registrar.disable();
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

function editarRegistro(id) {
    $.confirm({
        title: 'Editar registros',
        content: 'url:/admin/sistema/parametros/to/editarRegistro/' + id,
        columnClass: 'col-md-8 col-md-offset-2',
        buttons: {
            actualizar: {
                text: 'Actualizar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/parametros/to/guardarCambios',
                            dataType: 'html',
                            method: 'post',
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: new FormData(frm[0]),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.actualizar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerRegistros();
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

function filtrarRegistros() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: '/admin/sistema/parametros/to/filtrarRegistros',
        dataType: 'html',
        data: {
            criterio: $('#criterios').val(),
            parametro: $('#filtrarRegistros').val()
        },
        success: function (data) {
            $('#listadoRegistros').empty().html(data);
        }
    });
}