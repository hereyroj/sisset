$(document).ready(function () {
    obtenerRegistros();
});

function obtenerRegistros() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/sistema/calendario/obtenerRegistros/' + $('#year').val() + '/' + $('#month').val(),
        dataType: 'html',
        success: function (data) {
            $('#listadoRegistros').empty().html(data);
        }
    });
}

function nuevoRegistro() {

}

function editarRegistro($id) {
    $.confirm({
        title: 'Editar registro',
        content: 'url:/admin/sistema/calendarios/editarRegistro/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/sistema/calendarios/actualizarRegistro',
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
                        obtenerRegistros();
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