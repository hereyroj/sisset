$(document).ready(function () {
    obtenerTramites();
});

function obtenerTramites() {
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
                    $('#tramites').append('<div class="checkbox"><label><input type="checkbox" name="tramites[]" value="' + key + '">' + val + '</label></div>');
                })
            }
        }
    });
}

$('#grupo').on('change', function () {
    obtenerTramites();
});