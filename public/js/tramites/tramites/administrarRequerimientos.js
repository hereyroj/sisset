function obtenerRequerimientos() {
    $.ajax({
        url: '/admin/tramites/tramites/obtenerRequerimientos/' + $('#tramite').val(),
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#listadoRequerimientos').empty().html(response);
    }).fail(function () {
        $.alert({
            title: 'Advertencia!',
            content: 'Se ha producido un error al intentar obtener los requerimientos.',
        });
    });
}

function crearRequerimiento() {
    $.confirm({
        title: 'Crear requerimiento',
        onOpenBefore: function () {
            var frm = $('#frmCrearRequerimiento');
            var self = this;
            $.ajax({
                url: '/admin/tramites/tramites/crearRequerimiento',
                dataType: 'json',
                method: 'post',
                data: frm.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (response) {
                if (response === true) {
                    self.setContent('<div class="alert alert-success">Se ha creado el requerimiento.</div>');
                    obtenerRequerimientos();
                } else {
                    self.setContent(response);
                }
            }).fail(function () {
                self.setContent('<div class="alert alert-danger">Ha ocurrido un error al crear el requerimiento.</div>');
            });

        },
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function editarRequerimiento(id) {
    $.confirm({
        title: 'Editar requerimiento',
        content: 'url:/admin/tramites/tramites/editarRequerimiento/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/tramites/editarRequerimiento',
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
                        obtenerRequerimientos();
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
                action: function () {}
            }
        }
    });
}