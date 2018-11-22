// require('bootstrap');

$("map[name=pond]").mapoid(
    {mouseover: function(e){(border_sector (e.target.getAttribute('sector')));},
        click: function(e){alert('Paspausta ant '+e.target.getAttribute('sector')+' sektoriaus');},
        fillColor: "#a1f7cc",
    }
);

function border_sector (id) {
    $('.sectors').removeClass('highlight');
    $('#'+id).addClass('highlight');
}

$(document).on('click', '.sectors_day_cell', function(e) {
    console.log(e);
});


$(document).on('click', '.side_panel', function() {
    // Set the effect type
    var effect = 'slide';

    // Set the options for the effect type chosen
    var options = { direction: 'right' };

    // Set the duration (default: 400 milliseconds)
    var duration = 700;

    $('.side_panel').toggle(effect, options, duration);
});






