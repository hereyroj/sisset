$(document).ready(function () {
    obtenerEmpresas();
});

function obtenerEmpresas() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/sistema/empresasTransporte/obtenerEmpresas',
        }).done(function (data) {
            $('#empresas').find('table').remove();
            $('#empresas').find('.text-center').remove();
            $('#empresas').append(data);
        })
        .fail(function () {

        });
}

$(document).on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#empresas').find('table').remove();
            $('#empresas').find('.text-center').remove();
            $('#empresas').append(data);
        }
    });
});

function editarEmpresa(id) {
    $.confirm({
        title: 'Editar tramite',
        content: 'url:/admin/sistema/empresasTransporte/editar/' + id,
        buttons: {
            actualizar: {
                text: 'Actualizar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/empresasTransporte/editar',
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
                            obtenerEmpresas();
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

function nuevaEmpresa() {
    $.confirm({
        title: 'Nueva empresa',
        content: 'url:/admin/sistema/empresasTransporte/nueva',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/empresasTransporte/crear',
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
                            obtenerEmpresas();
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