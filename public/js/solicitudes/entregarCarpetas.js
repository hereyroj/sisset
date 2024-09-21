function filtrarSinEntregar() {
    if ($('#filtrarSinEntregar').val() == "" || $('#criteriosSinEntregar').val() == "") {
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
                url: '/admin/solicitudes/administracion/filtro/sinEntregar/' + $('#filtrarSinEntregar').val() + '/' + $('#criteriosSinEntregar').val(),
                dataType: "html"
            }).done(function (data) {
                $('#sinEntregar').find('table').remove();
                $('#sinEntregar').find('.text-center').remove();
                $('#sinEntregar').append(data);
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
    solicitudesSinEntregar();
});

function solicitudesSinEntregar() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/solicitudes/administracion/sinEntregar',
            dataType: "html"
        }).done(function (data) {
            $('#sinEntregar').find('table').remove();
            $('#sinEntregar').find('.text-center').remove();
            $('#sinEntregar').append(data);
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

function entregarCarpeta(id) {
    $.confirm({
        title: 'Entregar carpeta',
        content: 'url:/admin/solicitudes/administracion/entregarCarpeta/' + id,
        buttons: {
            entregar: {
                text: 'Entregar',
                btnClass: 'btn-red',
                action: function () {
                    var self = this;
                    var form = this.$content.find('form');
                    $.ajax({
                        url: '/admin/solicitudes/administracion/entregarCarpeta',
                        dataType: 'html',
                        method: 'post',
                        data: form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.entregar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        solicitudesSinEntregar();
                    }).fail(function () {
                        self.buttons.entregar.disable();
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