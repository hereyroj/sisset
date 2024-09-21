$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

var fecha = $('#publicationDateEdit').val();
var $input = $('#publicationDateEdit').pickadate();
var picker = $input.pickadate('picker');
picker.set('select', fecha, {format: 'yyyy-mm-dd'});