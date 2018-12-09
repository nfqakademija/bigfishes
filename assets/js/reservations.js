const urlParams = new URLSearchParams(window.location.search);
const dateMin = urlParams.get('date');



$('#reservation_dateTo').css('width', '12em');

$('#reservation_dateTo').attr({
    min:dateMin,
});




