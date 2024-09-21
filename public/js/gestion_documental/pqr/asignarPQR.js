$('input:radio').change(function (e) {
    $('input[value="' + e.value + '"]').prop('checked', true);
});