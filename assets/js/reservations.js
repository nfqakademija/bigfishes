const urlParams = new URLSearchParams(window.location.search);
const dateMin = urlParams.get('date');
$('#reservation_dateTo').css('width', '12em');

$('#reservation_dateTo').attr({
    min:dateMin,
});

if ($('.reservation_form')[0].children[0].childNodes[1].childNodes[1].childNodes[3].innerHTML === 'Blogai pasirinktas sektorius') {
    $('form').remove();
    $('.reservation_form .box-success').append('<div>' +
                                                    '<a class="submit_button mt-2 mb-2" href="./" style="color:white">Grižti į kalendorių</a>' +
                                               '</div>')
}





