const urlParams = new URLSearchParams(window.location.search);
const datePressed = urlParams.get('date');

if ($(window).width() < 600) {
    $('.left_button-back').text('').append('<i class="fa fa-angle-double-left" style="color: white; font-size: 1em"></i>');
}

if (datePressed == date_from_BUSY && time_from_BUSY === '20') {
        $('input#reservation_timeFrom_1').attr('disabled', true);
        $('input#reservation_timeTo_0').attr('disabled', true);
        $('input#reservation_timeFrom_0').attr('checked', 'checked');
        $('input#reservation_timeTo_1').attr('checked', 'checked');
}

if(!isFromAvailableFrom8){
    $('input#reservation_timeFrom_0').attr({'disabled': true, 'checked': ''});
    $('input#reservation_timeFrom_1').attr('checked', 'checked');
}








if ($('.wrong_sector_title')[0].innerHTML && $('.wrong_sector_title')[0].innerHTML === 'Blogai pasirinktas sektorius') {
    $('form').remove();
    $('.reservation_form .box-success').append('<div>' +
                                                    '<a class="submit_button mt-2 mb-2" href="./" style="color:white">Grižti į kalendorių</a>' +
                                               '</div>')
}









