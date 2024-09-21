$('#attachments').on('change', function () {
    var files = $('#attachments').prop("files");
    var names = $.map(files, function (val) {
        return val.name;
    });
    $('#listado-adjuntos').empty();
    $.each(names, function (key, value) {
        $('#listado-adjuntos').append('<li class="list-group-item">' + value + '</li>');
    });
});