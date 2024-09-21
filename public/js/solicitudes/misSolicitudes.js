$(document).ready(function () {
    obtenerMisSolicitudes();
});

function obtenerMisSolicitudes() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/solicitudes/misSolicitudes/obtenerMisSolicitudes',
        dataType: "html"
    }).done(function (data) {
        $('#misSolicitudes').find('table').remove();
        $('#misSolicitudes').find('.text-center').remove();
        $('#misSolicitudes').append(data);
    })
}

function solicitarCarpeta() {
    $.confirm({
        title: 'Solicitar carpeta',
        content: 'url:/admin/solicitudes/misSolicitudes/crear',
        buttons: {
            solicitar: {
                text: 'Solicitar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/solicitudes/misSolicitudes/registrar',
                            dataType: 'html',
                            method: 'post',
                            data: this.$content.find('form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.solicitar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerMisSolicitudes();
                        }).fail(function () {
                            self.buttons.solicitar.disable();
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