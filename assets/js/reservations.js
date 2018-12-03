const urlParams = new URLSearchParams(window.location.search);
const dateMin = urlParams.get('date');


$('#reservation_dateTo').css('width', '12em');

// $('input[type=radio][value=08]').removeAttr('checked');
// $('input[type=radio][value=08]').attr('disabled', true);
// $('input[type=radio][value=20]').attr('checked','checked');

$('#reservation_dateTo').attr({
    min:dateMin,
    value:dateMin
});

