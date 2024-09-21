var PageCoEx = 1;
var PageCoIn = 1;
var PageCoSa = 1;
$(document).ready(function () {
    misCoEx();
    misCoIn();
    misCoSa();
});

$(document).on("click", "#correspondencia_externa .pagination li a", function (e) {
    PageCoEx = $(this).attr('href').split('page=')[1];
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#correspondencia_externa').empty().html(data);
        }
    });
});

$(document).on("click", "#correspondencia_interna .pagination li a", function (e) {
    PageCoIn = $(this).attr('href').split('page=')[1];
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#correspondencia_interna').empty().html(data);
        }
    });
});

$(document).on("click", "#correspondencia_saliente .pagination li a", function (e) {
    PageCoSa = $(this).attr('href').split('page=')[1];
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#correspondencia_saliente').empty().html(data);
        }
    });
});

function misCoEx() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/mis-pqr/obtenerMisProcesosCoEx?page=' + PageCoEx,
        }).done(function (data) {
            $('#correspondencia_externa').empty().html(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {}
                    }
                }
            });
        });
}

function misCoIn() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/mis-pqr/obtenerMisProcesosCoIn?page=' + PageCoIn,
        }).done(function (data) {
            $('#correspondencia_interna').empty().html(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {}
                    }
                }
            });
        });
}

function misCoSa() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/mis-pqr/obtenerMisProcesosCoSa?page=' + PageCoSa,
        }).done(function (data) {
            $('#correspondencia_saliente').empty().html(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {}
                    }
                }
            });
        });
}

function verHistorialAsignaciones(id) {
    $.confirm({
        title: 'Historial de asignaciones',
        content: 'url:/admin/mis-pqr/historialAsignaciones/' + id,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verClasificacion(id, pqr) {
    $.confirm({
        title: 'Clasificación PQR',
        content: 'url:/admin/mis-pqr/verClasificacion/' + id + '/' + pqr,
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

function verRespuesta(id) {
    $.confirm({
        title: 'Ver respuesta',
        content: 'url:/admin/mis-pqr/verRespuesta/' + id,
        onContentReady: function () {
            this.$content.find('input, textarea, button, select').attr('disabled', 'disabled');
            this.$content.find('a').click(function () {
                window.document.location = $(this).attr("href");
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

function verAsunto(id) {
    $.confirm({
        title: 'Ver asunto',
        content: 'url:/admin/mis-pqr/verAsunto/' + id,
        onContentReady: function () {
            this.$content.find('a').click(function () {
                window.document.location = $(this).attr("href");
            });
        },
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}

function registrarEnvio(id) {
    $.confirm({
        title: 'Registrar envío',
        content: 'url:/admin/mis-pqr/registrarEnvio/' + id,
        buttons: {
            registrar: {
                text: 'Registrar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/mis-pqr/registrarEnvio',
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
                        misCoSa();
                    }).fail(function () {
                        self.buttons.registrar.disable();
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

function verEntrega(id) {
    $.alert({
        title: 'Consultado...',
        theme: 'light',
        type: 'blue',
        typeAnimated: true,
        content: function () {
            var self = this;
            return $.ajax({
                url: '/admin/mis-pqr/verEntrega/' + id,
                dataType: 'html',
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (response) {
                self.setTitle('Finalizado');
                self.setContent(response);
            }).fail(function () {
                self.setTitle('Error!');
                self.setContent('¡¡No se pudo realizar la acción!!');
            });
        },
        onContentReady: function () {
            this.$content.find('a').click(function () {
                window.document.location = $(this).attr("href");
            });
        },
        buttons: {
            cerrar: {
                text: 'Cerrar',
                btnClass: 'btn-blue',
                action: function () {

                }
            }
        }
    });
}

function registrarEntrega(id) {
    $.confirm({
        title: 'Registrar entrega',
        content: 'url:/admin/mis-pqr/registrarEntrega/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/mis-pqr/registrarEntrega',
                            dataType: 'html',
                            method: 'post',
                            data: new FormData(frm[0]),
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            misCoSa();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la creación del proceso.',
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

function filtrarMisCoSa() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/mis-pqr/filtrar/CoSa/' + $('#filtrarCoSa').val() + '/' + $('#filtroCoSa').val(),
        }).done(function (data) {
            if (data != null && data != undefined && data != '') {
                $('#correspondencia_saliente').empty().prepend(data);
            } else {
                $.alert({
                    title: 'Error!',
                    content: 'No se ha encontrado registros con la información suministrada.',
                    buttons: {
                        cerrar: {
                            text: 'Cerrar',
                            action: function () {}
                        }
                    }
                });
            }
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {}
                    }
                }
            });
        });
}

function filtrarMisCoIn() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/mis-pqr/filtrar/CoIn/' + $('#filtrarCoIn').val() + '/' + $('#filtroCoIn').val(),
        }).done(function (data) {
            if (data != null && data != undefined && data != '') {
                $('#correspondencia_interna').empty().prepend(data);
            } else {
                $.alert({
                    title: 'Error!',
                    content: 'No se ha encontrado registros con la información suministrada.',
                    buttons: {
                        cerrar: {
                            text: 'Cerrar',
                            action: function () {}
                        }
                    }
                });
            }
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {}
                    }
                }
            });
        });
}

function filtrarMisCoEx() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/mis-pqr/filtrar/CoEx/' + $('#filtrarCoEx').val() + '/' + $('#filtroCoEx').val(),
        }).done(function (data) {
            if (data != null && data != undefined && data != '') {
                $('#correspondencia_externa').empty().prepend(data);
            } else {
                $.alert({
                    title: 'Error!',
                    content: 'No se ha encontrado registros con la información suministrada.',
                    buttons: {
                        cerrar: {
                            text: 'Cerrar',
                            action: function () {}
                        }
                    }
                });
            }
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {}
                    }
                }
            });
        });
}

function verEnvio(id) {
    $.confirm({
        title: 'Información del envío',
        content: 'url:/admin/mis-pqr/verEnvio/' + id,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verPeticionario(id) {
    $.confirm({
        title: 'Información del peticionario',
        content: 'url:/admin/mis-pqr/verPeticionario/' + id,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}