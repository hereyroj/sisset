var crearPqr;
var PageCoEx = 1;
var PageCoIn = 1;
var PageCoSa = 1;
$(document).ready(function () {
    obtenerCoEx();
    obtenerCoIn();
    obtenerCoSa();
    obtenerMedios();
    obtenerClases();
    obtenerModalidades();
    obtenerMotivosAnulacion();
});

$('.datepicker').pickadate({
    container: 'body'
});

function obtenerCoEx() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/pqr/obtenerAllCoEx?page='+PageCoEx,
    }).done(function (data) {
        $('#correspondencia_externa').empty().prepend(data);
    })
        .fail(function () {
            $.alert({
                title: 'Error',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {
                        }
                    }
                }
            });
        });
}

function obtenerCoIn() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/pqr/obtenerAllCoIn?page='+PageCoIn,
    }).done(function (data) {
        $('#correspondencia_interna').empty().prepend(data);
    })
        .fail(function () {
            $.alert({
                title: 'Error',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {
                        }
                    }
                }
            });
        });
}

function obtenerCoSa() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/pqr/obtenerAllCoSa?page='+PageCoSa,
    }).done(function (data) {
        $('#correspondencia_saliente').empty().prepend(data);
    })
        .fail(function () {
            $.alert({
                title: 'Error',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {
                        }
                    }
                }
            });
        });
}

function verHistorialAsignaciones(id) {
    $.confirm({
        title: 'Historial de asignaciones',
        content: 'url:/admin/pqr/historialAsignaciones/' + id,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                }
            }
        }
    });
}

function asignarPqr(id) {
    $.confirm({
        title: 'Asignar PQR',
        content: 'url:/admin/pqr/asignar/' + id,
        columnClass: 'col-md-8 col-md-offset-2',
        buttons: {
            asignar: {
                text: 'Asignar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/pqr/asignar',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.asignar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerCoEx();
                            obtenerCoIn();
                        }).fail(function () {
                            self.buttons.asignar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la asignación del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
                                    }
                                }
                            }
                        });
                        return false;
                    }
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

function reAsignar(id) {
    $.confirm({
        title: 'Re-asignar PQR',
        content: 'url:/admin/pqr/reAsignar/' + id,
        columnClass: 'col-md-8 col-md-offset-2',
        buttons: {
            asignar: {
                text: 'Asignar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/pqr/reAsignar',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.asignar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerCoEx();
                            obtenerCoIn();
                        }).fail(function () {
                            self.buttons.asignar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la asignación del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
                                    }
                                }
                            }
                        });
                        return false;
                    }
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

function clasificarPqr(id) {
    $.confirm({
        title: 'Clasificar PQR',
        content: 'url:/admin/pqr/clasificar/' + id,
        buttons: {
            clasificar: {
                text: 'Clasificar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/pqr/clasificar',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.clasificar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerCoEx();
                            obtenerCoIn();
                            obtenerCoSa();
                        }).fail(function () {
                            self.buttons.clasificar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la clasificación del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
                                    }
                                }
                            }
                        });
                        return false;
                    }
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

function verClasificacion(id, pqr) {
    $.confirm({
        title: 'Clasificación PQR',
        content: 'url:/admin/pqr/verClasificacion/' + id + '/' + pqr,
        onContentReady: function () {
            this.buttons.clasificar.hide();
            this.$content.find('input, textarea, button, select').attr('disabled', 'disabled');
        },
        buttons: {
            cambiar: {
                text: 'Cambiar',
                btnClass: 'btn-green',
                action: function () {
                    var self = this;
                    this.$content.find('input, textarea, button, select').removeAttr('disabled');
                    self.buttons.clasificar.show();
                    self.buttons.cambiar.hide();
                    return false;
                }
            },
            clasificar: {
                text: 'Clasificar',
                btnClass: 'btn-blue',
                isHidden: true,
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/pqr/editarClasificacion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.clasificar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerCoEx();
                            obtenerCoIn();
                        }).fail(function () {
                            self.buttons.clasificar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la clasificación del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
                                    }
                                }
                            }
                        });
                        return false;
                    }
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

function verRespuesta(id) {
    $.confirm({
        title: 'Ver respuesta',
        content: 'url:/admin/pqr/verRespuesta/' + id,
        onContentReady: function () {
            this.$content.find('input, textarea, button, select').attr('disabled', 'disabled');
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

function responderPqr(pqr, asignacion) {
    $.confirm({
        title: 'Responder PQR',
        content: 'url:/admin/pqr/responder/' + pqr + '/' + asignacion,
        buttons: {
            responder: {
                text: 'Responder',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/pqr/responder',
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
                            self.buttons.responder.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerCoEx();
                            obtenerCoIn();
                        }).fail(function () {
                            self.buttons.responder.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
                                    }
                                }
                            }
                        });
                        return false;
                    }
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

function crearCoEx() {
    crearPqr = $.confirm({
        title: 'Crear CoEx',
        content: 'url:/admin/pqr/crearCoEx',
        columnClass: 'col-md-8 col-md-offset-2',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/pqr/nuevoCoExAd',
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
                            self.setContent(response);
                            self.setTitle('Terminado');
                            if(self.$content.find('#idPqr').length >= 1){
                                self.buttons.cerrar.hide();
                                self.buttons.crear.hide();
                            }
                            $('div.jconfirm-scrollpane').scrollTop(0);
                            if(self.$content.find('#file')){
                                self.buttons.crear.disable();
                                self.buttons.cerrar.disable();
                            }
                            obtenerCoEx();
                        }).fail(function () {
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en el proceso de creación.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
                                    }
                                }
                            }
                        });
                        return false;
                    }
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

function crearCoIn() {
    crearPqr = $.confirm({
        title: 'Crear CoIn',
        content: 'url:/admin/pqr/crearCoIn',
        columnClass: 'col-md-8 col-md-offset-2',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/pqr/nuevoCoIn',
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
                            self.setContent(response);
                            self.setTitle('Terminado');
                            if(self.$content.find('#idPqr').length >= 1){
                                self.buttons.cerrar.hide();
                                self.buttons.crear.hide();
                            }
                            $('div.jconfirm-scrollpane').scrollTop(0);
                            obtenerCoIn();
                        }).fail(function () {
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
                                    action: function () {
                                    }
                                }
                            }
                        });
                        return false;
                    }
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

function crearCoSa() {
    crearPqr = $.confirm({
        title: 'Crear CoSa',
        content: 'url:/admin/pqr/crearCoSa',
        columnClass: 'col-md-8 col-md-offset-2',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/pqr/nuevoCoSa',
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
                            self.setContent(response);
                            self.setTitle('Terminado');
                            if(self.$content.find('#idPqr').length >= 1){
                                self.buttons.cerrar.hide();
                                self.buttons.crear.hide();
                            }
                            $('div.jconfirm-scrollpane').scrollTop(0);
                            obtenerCoSa();
                        }).fail(function () {
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
                                    action: function () {
                                    }
                                }
                            }
                        });
                        return false;
                    }
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

function filtrarCoEx() {
    if($('#filtrarCoEx').val() == undefined){
        $.alert({
                title: 'Error!',
                content: 'No se ha especificado el valor de búsqueda.',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {
                            $('#filtrarCoEx').focus();
                        }
                    }
                }
            });
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/pqr/filtrar/CoEx/' + $('#filtrarCoEx').val() + '/' + $('#filtroCoEx').val(),
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
                        action: function () {
                        }
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
                        action: function () {
                        }
                    }
                }
            });
        });
}

function filtrarCoIn() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/pqr/filtrar/CoIn/' + $('#filtrarCoIn').val() + '/' + $('#filtroCoIn').val(),
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
                        action: function () {
                        }
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
                        action: function () {
                        }
                    }
                }
            });
        });
}

function filtrarCoSa() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/pqr/filtrar/CoSa/' + $('#filtrarCoSa').val() + '/' + $('#filtroCoSa').val(),
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
                        action: function () {
                        }
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
                        action: function () {
                        }
                    }
                }
            });
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
                url: '/admin/pqr/verEntrega/' + id,
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

function verEnvio(id) {
    $.alert({
        title: 'Consultado...',
        theme: 'light',
        type: 'blue',
        typeAnimated: true,
        content: function () {
            var self = this;
            return $.ajax({
                url: '/admin/mis-pqr/verEnvio/' + id,
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

function obtenerMedios() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/pqr/cargarMediosTraslado',
        dataType: 'html',
    }).done(function (data) {
        $('#medios').empty().html(data);
    });
}

function obtenerClases() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/pqr/cargarClases',
        dataType: 'html',
    }).done(function (data) {
        $('#clases').empty().html(data);
    });
}

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

function verAsunto(id) {
    $.confirm({
        title: 'Ver asunto',
        content: 'url:/admin/pqr/verAsunto/' + id,
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

function nuevaClase() {
    $.confirm({
        title: 'Nueva clase',
        content: 'url:/admin/pqr/nuevaClase',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/crearClase',
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
                        obtenerClases();
                    }).fail(function () {
                        self.buttons.crear.disable();
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

function nuevoMedio() {
    $.confirm({
        title: 'Nuevo medio',
        content: 'url:/admin/pqr/nuevoMedio',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/crearMedio',
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
                        obtenerMedios();
                    }).fail(function () {
                        self.buttons.crear.disable();
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

function editarClase(id) {
    $.confirm({
        title: 'Editar clase',
        content: 'url:/admin/pqr/cargarClase/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/modificarClase',
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
                        obtenerClases();
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

function editarMedio(id) {
    $.confirm({
        title: 'Editar medio',
        content: 'url:/admin/pqr/cargarMedio/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/modificarMedio',
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
                        obtenerMedios();
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

function eliminarMedio(id) {
    $.confirm({
        icon: 'glyphicon glyphicon-warning-sign',
        title: 'Está seguro!',
        columnClass: 'medium',
        content: 'Está seguro de querer eliminar este elemento?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-primary',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: '/admin/pqr/eliminarMedio/' + id,
                        dataType: 'html',
                    }).done(function (data) {
                        obtenerMedios();
                        self.setContent(data);
                        self.buttons.eliminar.disable();
                    });
                    return false;
                }
            },
            cancel: {
                text: 'Cancelar',
                btnClass: 'btn-danger',
            },
        }
    });
}

function eliminarClase(id) {
    $.confirm({
        icon: 'glyphicon glyphicon-warning-sign',
        title: 'Está seguro!',
        columnClass: 'medium',
        content: 'Está seguro de querer eliminar este elemento?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-primary',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: '/admin/pqr/eliminarClase/' + id,
                        dataType: 'html',
                    }).done(function (data) {
                        obtenerClases();
                        self.setContent(data);
                        self.buttons.eliminar.disable();
                    });
                    return false;
                }
            },
            cancel: {
                text: 'Cancelar',
                btnClass: 'btn-danger',
            },
        }
    });
}

function restaurarMedio(id) {
    $.alert({
        title: 'Restaurar medio',
        content: 'url:/admin/pqr/restaurarMedio/' + id,
        onContentReady: function () {
            obtenerMedios();
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

function restaurarClase(id) {
    $.alert({
        title: 'Restaurar clase',
        content: 'url:/admin/pqr/restaurarClase/' + id,
        onContentReady: function () {
            obtenerClases();
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

function obtenerModalidades() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/pqr/cargarModalidades',
        dataType: 'html'
    }).done(function (data) {
        $('#modalidades').empty().html(data);
    });
}

function nuevaModalidad() {
    $.confirm({
        title: 'Nueva modalidad',
        content: 'url:/admin/pqr/nuevaModalidad',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/crearModalidad',
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
                        obtenerModalidades();
                    }).fail(function () {
                        self.buttons.crear.disable();
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

function restaurarModalidad(id) {
    $.alert({
        title: 'Restaurar clase',
        content: 'url:/admin/pqr/restaurarModalidad/' + id,
        onContentReady: function () {
            obtenerModalidades();
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

function eliminarModalidad(id) {
    $.confirm({
        icon: 'glyphicon glyphicon-warning-sign',
        title: 'Está seguro!',
        columnClass: 'medium',
        content: 'Está seguro de querer eliminar este elemento?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-primary',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: '/admin/pqr/eliminarModalidad/' + id,
                        dataType: 'html',
                    }).done(function (data) {
                        obtenerModalidades();
                        self.setContent(data);
                        self.buttons.eliminar.disable();
                    });
                    return false;
                }
            },
            cancel: {
                text: 'Cancelar',
                btnClass: 'btn-danger',
            },
        }
    });
}

function editarModalidad(id) {
    $.confirm({
        title: 'Editar medio',
        content: 'url:/admin/pqr/editarModalidad/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/actualizarModalidad',
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
                        obtenerModalidades();
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

function cambiarFechaLimite(id) {
    $.confirm({
        title: 'Cambiar fecha limite PQR',
        content: 'url:/admin/pqr/cambiarFechaLimite/' + id,                
        buttons: {
            cambiar: {
                text: 'Cambiar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/cambiarFechaLimite',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.cambiar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCoEx();
                        obtenerCoIn();
                    }).fail(function () {
                        self.buttons.cambiar.disable();
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

function reUploadFileRadicado(id) {
    $.confirm({
        title: 'Subir archivo radicado',
        content: 'url:/admin/pqr/reUploadFileRadicado/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        containerFluid: true,
        container: 'body',
        buttons: {
            subir: {
                text: 'Subir',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        $.ajax({
                            url: '/admin/pqr/reUploadFileRadicado',
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
                            self.buttons.subir.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                        }).fail(function () {
                            self.buttons.subir.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    }else{
                        $.alert('Error en la validación del formulario');
                    }
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

function reGenerarPDF(id) {
    $.confirm({
        title: 'Generando PDF',
        content: 'url:/admin/pqr/reGenerarPDF/' + id,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}

function vincularRadicadosEntrada(id) {
    $.confirm({
        title: 'Vincular radicados',
        content: 'url:/admin/pqr/vincularRadicadosEntrada/' + id,
        buttons: {
            vincular: {
                text: 'Vincular',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    var frm = this.$content.find('form');
                    $.ajax({
                        url: '/admin/pqr/vincularRadicadosEntrada',
                        dataType: 'html',
                        method: 'post',
                        data: frm.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        obtenerCoSa();
                        self.buttons.vincular.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                    }).fail(function () {
                        self.buttons.vincular.disable();
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

function cambiarClase(id) {
    $.confirm({
        title: 'Cambiar clase PQR',
        content: 'url:/admin/pqr/cambiarClase/' + id,
        buttons: {
            cambiar: {
                text: 'Cambiar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/cambiarClase',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.cambiar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCoEx();
                        obtenerCoIn();
                        obtenerCoSa();
                    }).fail(function () {
                        self.buttons.cambiar.disable();
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

function cambiarFuncionario(id, funcionario) {
    $.confirm({
        title: 'Cambiar Funcionario',
        content: 'url:/admin/pqr/cambiarFuncionario/' + id + '/' + funcionario,
        buttons: {
            cambiar: {
                text: 'Cambiar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/cambiarFuncionario',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.cambiar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCoIn();
                        obtenerCoSa();
                    }).fail(function () {
                        self.buttons.cambiar.disable();
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

function opcionesRadicadoContestacion(id, radicado){
    $.confirm({
        title: 'Obciones del radicado',
        content: '¿Qúe desea hacer?',
        onOpenBefore: function () {
            this.buttons.guardar.hide();
        },
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    self.buttons.eliminar.hide();
                    $.ajax({
                        url: '/admin/pqr/modificarRadicadoRespuesta/'+id+'/'+radicado,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.editar.hide();
                        self.buttons.guardar.show();
                        self.setContent(response);
                        self.setTitle('Editar radicado');
                        obtenerCoSa();
                    }).fail(function () {
                        self.buttons.editar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
                }
            },
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-green',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/modificarRadicadoRespuesta',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.guardar.hide();
                        self.setContent(response);
                        self.setTitle('Realizado');
                        obtenerCoSa();
                    }).fail(function () {
                        self.buttons.guardar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
                }
            },
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-red',
                action: function () {
                    var self = this;
                    self.buttons.editar.hide();
                    $.ajax({
                        url: '/admin/pqr/desvincularRadicado/'+id+'/'+radicado,
                        dataType: 'html',
                        method: 'get',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.eliminar.hide();
                        self.setContent(response);
                        self.setTitle('Realizado');
                        obtenerCoSa();
                    }).fail(function () {
                        self.buttons.eliminar.disable();
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

function cambiarMedioTraslado(id) {
    $.confirm({
        title: 'Cambiar medio de traslado',
        content: 'url:/admin/pqr/cambiarMedioTraslado/' + id,
        buttons: {
            cambiar: {
                text: 'Cambiar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/cambiarMedioTraslado',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.cambiar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCoEx();
                        obtenerCoIn();
                        obtenerCoSa();
                    }).fail(function () {
                        self.buttons.cambiar.disable();
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

function anularProceso(id) {
    $.confirm({
        title: 'Anular proceso',
        content: 'url:/admin/pqr/anular/'+id,
        buttons: {
            anular: {
                text: 'Anular',
                btnClass: 'btn-red',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/anular',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.anular.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCoEx();
                        obtenerCoIn();
                        obtenerCoSa();
                    }).fail(function () {
                        self.buttons.crear.disable();
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

function nuevoMotivoAnulacion() {
    $.confirm({
        title: 'Nuevo motivo anulación',
        content: 'url:/admin/pqr/nuevoMotivoAnulacion',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/nuevoMotivoAnulacion',
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
                        obtenerMotivosAnulacion();
                    }).fail(function () {
                        self.buttons.crear.disable();
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

function editarMotivoAnulacion(id) {
    $.confirm({
        title: 'Editar motivo anulación',
        content: 'url:/admin/pqr/editarMotivoAnulacion/'+id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/pqr/editarMotivoAnulacion',
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
                        obtenerMotivosAnulacion();
                    }).fail(function () {
                        self.buttons.crear.disable();
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

function obtenerMotivosAnulacion() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/pqr/obtenerMotivosAnulacion',
    }).done(function (data) {
        $('#motivos_anulacion').empty().prepend(data);
    })
        .fail(function () {
            $.alert({
                title: 'Error',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {
                        }
                    }
                }
            });
        });
}

function verAnulacion(id) {
    $.confirm({
        title: 'Ver anulación',
        content: 'url:/admin/pqr/verAnulacion/' + id,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}