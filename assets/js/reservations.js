const urlParams = new URLSearchParams(window.location.search);
const dateMin = urlParams.get('date');
$('#reservation_dateTo').attr({
    min:dateMin,
    value:dateMin
});