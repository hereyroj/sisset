function editarCuota(id) {
    $.confirm({
        title: 'Editar cuota',
        content: 'url:/admin/inspeccion/AcuerdosPagos/editarCuota/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        var acuerdoId = this.$content.find('#acuerdo_id').val();
                        $.ajax({
                            url: '/admin/inspeccion/AcuerdosPagos/editarCuota',
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
                            $.ajax({
                                url: '/admin/inspeccion/AcuerdosPagos/obtenerCuotasAcuerdoPago/' + acuerdoId,
                                dataType: 'html',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            }).done(function (response) {
                                listadoCuotas.setContent(response);
                            })
                        }).fail(function () {
                            self.buttons.guardar.disable();
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

function pagarCuota(id) {
    $.confirm({
        title: 'Pagar cuota',
        content: 'url:/admin/inspeccion/AcuerdosPagos/pagarCuota/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        var acuerdoId = this.$content.find('#acuerdo_id').val();
                        var formData = new FormData(this.$content.find('form')[0]);
                        $.ajax({
                            url: '/admin/inspeccion/AcuerdosPagos/pagarCuota',
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
                            $.ajax({
                                url: '/admin/inspeccion/AcuerdosPagos/obtenerCuotasAcuerdoPago/' + acuerdoId,
                                dataType: 'html',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            }).done(function (response) {
                                listadoCuotas.setContent(response);
                            })
                        }).fail(function () {
                            self.buttons.guardar.disable();
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

function editarPagoCuota(id) {
    $.confirm({
        title: 'Editar pago cuota',
        content: 'url:/admin/inspeccion/AcuerdosPagos/editarPagoCuota/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        var acuerdoId = this.$content.find('#acuerdo_id').val();
                        var formData = new FormData(this.$content.find('form')[0]);
                        $.ajax({
                            url: '/admin/inspeccion/AcuerdosPagos/editarPagoCuota',
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
                            $.ajax({
                                url: '/admin/inspeccion/AcuerdosPagos/obtenerCuotasAcuerdoPago/' + acuerdoId,
                                dataType: 'html',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            }).done(function (response) {
                                listadoCuotas.setContent(response);
                            })
                        }).fail(function () {
                            self.buttons.guardar.disable();
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