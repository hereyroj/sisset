$(document).ready(function () {
    obtenerTramites();
});

function obtenerTramites() {
    var tramitesSolicitud = "{{json_encode($solicitud->hasTramites->pluck('id'))}}";

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/sistema/tramitesGrupos/obtenerTramites/' + $('#grupo').val(),
        success: function (data) {
            $('#tramites').find('div.checkbox').remove();
            if (data != undefined && data != null) {
                $.each(data, function (key, val) {
                    if ($.inArray(key, tramitesSolicitud) > -1) {
                        $('#tramites').append('<div class="checkbox"><label><input type="checkbox" name="tramites[]" value="' + key + '" checked>' + val + '</label></div>');
                    } else {
                        $('#tramites').append('<div class="checkbox"><label><input type="checkbox" name="tramites[]" value="' + key + '">' + val + '</label></div>');
                    }
                })
            }
        }
    });
}

$('#grupo').on('change', function () {
    obtenerTramites();
});