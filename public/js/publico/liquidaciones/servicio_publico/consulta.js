function nuevaLiquidacion(id) {
    $.confirm({
        title: 'Nueva liquidación',
        content: function () {
            var self = this;
            return $.ajax({
                url: '/servicios/liquidaciones/servicioPublico/nuevaLiquidacion',
                dataType: 'html',
                method: 'post',
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (response) {
                self.setContent(response);
            }).fail(function () {
                self.setContent('Error en la consulta al servidor.');
            });
        },
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/servicios/liquidaciones/servicioPublico/crearLiquidacion',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.setContent(response);
                        self.setTitle('Terminado');
                        actualizarLiquidaciones(id);
                    }).fail(function () {
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

function actualizarLiquidaciones(id) {
    $.ajax({
        url: '/servicios/liquidaciones/servicioPublico/obtenerLiquidaciones',
        dataType: 'html',
        method: 'post',
        data: {
            id: id
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#liquidacionesVehiculo').find('table').remove();
        $('#liquidacionesVehiculo').append(response);
    }).fail(function () {

    });
}