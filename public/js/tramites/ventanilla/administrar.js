$(document).ready(function () {
    obtenerVentanillas();
});

function obtenerVentanillas() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/ventanillas/obtenerVentanillas',
        dataType: 'html',
        success: function (data) {
            $('#listadoVentanillas').empty().html(data);
        }
    });
}

function nuevaVentanilla() {
    $.confirm({
        title: 'Nueva ventanilla',
        content: 'url:/admin/tramites/ventanillas/nuevaVentanilla',
        columnClass: 'col-md-8 col-md-offset-2',
        buttons: {
            registrar: {
                text: 'Registrar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/ventanillas/nuevaVentanilla',
                            dataType: 'html',
                            method: 'post',
                            data: this.$content.find('form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.registrar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerVentanillas();
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

function editarVentanilla(id) {
    $.confirm({
        title: 'Nuevos rangos',
        content: 'url:/admin/tramites/ventanillas/editarVentanilla/' + id,
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
                            url: '/admin/tramites/ventanillas/editarVentanilla',
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
                            obtenerVentanillas();
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