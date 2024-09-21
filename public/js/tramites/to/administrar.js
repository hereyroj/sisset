$(document).ready(function () {
    obtenerTSO();
});

function obtenerTSO() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/to/obtenerTSO',
        success: function (data) {
            $('.listadoTSO').empty().html(data);
        }
    });
}

$('.listadoTSO').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('.listadoTSO').empty().html(data);
        }
    });
});

function filtrarBusqueda() {
    if ($('#filtrarBusqueda').val() != '') {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/to/filtrarBusqueda/' + $('#filtrarBusqueda').val(),
            success: function (data) {
                $('.listadoTSO').empty().html(data);
            }
        });
    } else {
        alert('No ha suministrado un criterio válido');
        $('#filtrarBusqueda').focus();
    }
}

function nuevaTO() {
    $.confirm({
        title: 'Nueva Tarjeta de Operación',
        content: 'url:/admin/tramites/to/crear',
        columnClass: 'col-md-8 col-md-offset-2',
        onContentReady: function () {
            var self = this;
            this.$content.find('#placa').change(function () {
                $('#tipoVehiculo').prop('selectedIndex', 0);
                $('#tipoCarroceria').prop('selectedIndex', 0);
                $('#marcaVehiculo').prop('selectedIndex', 0);
                $('#claseCombustible').prop('selectedIndex', 0);
                $('#nivelServicio').prop('selectedIndex', 0);
                $('#razonSocial').prop('selectedIndex', 0);
                $('#radioOperacion').prop('selectedIndex', 0);
                $('#numeroInterno').val('');
                $('#numeroMotor').val('');
                $('#capacidadPasajeros').val('');
                $('#capacidadToneladas').val('');
                $('#modeloVehiculo').val('');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: '/admin/tramites/to/verificarVigencia/' + $(this).val(),
                    dataType: 'html',
                    success: function (data) {
                        self.$content.find('.alert').remove();
                        self.setContentPrepend(data);
                    }
                });
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: '/admin/tramites/to/obtenerDatosVehiculo/' + $(this).val(),
                    dataType: 'json',
                    success: function (data) {
                        if (data != null) {
                            $('#tipoVehiculo').val(data.vehiculo_clase_id);
                            $('#tipoCarroceria').val(data.vehiculo_carroceria_id);
                            $('#marcaVehiculo').val(data.vehiculo_marca_id);
                            $('#modeloVehiculo').val(data.modelo);
                            $('#claseCombustible').val(data.vehiculo_combustible_id);
                            $('#numeroMotor').val(data.numero_motor);
                            $('#capacidadPasajeros').val(data.capacidad_pasajeros);
                            $('#capacidadToneladas').val(data.capacidad_toneladas);
                            $('#nivelServicio').val(data.hasEmpresaActiva.pivot.nivel_servicio_id);
                            $('#razonSocial').val(data.hasEmpresaActiva.pivot.empresa_transporte_id);
                            $('#radioOperacion').val(data.hasEmpresaActiva.pivot.radio_operacion_id);
                            $('#numeroInterno').val(data.hasEmpresaActiva.pivot.numero_interno);
                        }
                    }
                });
            });
        },
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/to/crear',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerTSO();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la validación del formulario.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {}
                                }
                            }
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

function editarTO(id) {
    $.confirm({
        title: 'Editar Tarjeta de Operación',
        content: 'url:/admin/tramites/to/editar/' + id,
        columnClass: 'col-md-8 col-md-offset-2',
        onContentReady: function () {
            var self = this;
            this.$content.find('#placa').change(function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: '/admin/tramites/to/verificarVigencia/' + $(this).val(),
                    dataType: 'html',
                    success: function (data) {
                        self.$content.find('.alert').remove();
                        self.setContentPrepend(data);
                        if (self.$content.find('.alert-danger').length > 0) {
                            self.buttons.guardar.disable();
                        } else {
                            self.buttons.guardar.enable();
                        }
                    }
                });
            });
        },
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/to/editar',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.guardar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerTSO();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la validación del formulario.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {}
                                }
                            }
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

function verTO(id) {
    $.confirm({
        title: 'Ver Tarjeta de Operación',
        content: 'url:/admin/tramites/to/ver/' + id,
        columnClass: 'col-md-8 col-md-offset-2',
        onContentReady: function () {
            this.$content.find('input, textarea, button, select').attr('disabled', 'disabled');
        },
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}