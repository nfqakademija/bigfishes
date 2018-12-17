const urlParams = new URLSearchParams(window.location.search);
const dateMin = urlParams.get('date');
$('#reservation_dateTo').css('width', '12em');

$('#reservation_dateTo').attr({
    min:dateMin,
});

if ($('.wrong_sector_title')[0].innerHTML === 'Blogai pasirinktas sektorius') {
    $('form').remove();
    $('.reservation_form .box-success').append('<div>' +
                                                    '<a class="submit_button mt-2 mb-2" href="./" style="color:white">Grižti į kalendorių</a>' +
                                               '</div>')
}

if ($(window).width() < 600) {
    $('.left_button-back').text('').append('<i class="fa fa-angle-double-left" style="color: white; font-size: 1em"></i>');
}





