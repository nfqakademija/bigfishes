// require('bootstrap');


$("map[name=pond]").mapoid(
    {mouseover: function(e){(border_sector (e.target.getAttribute('sector')));},
        // click: function(e){alert('Paspausta ant '+e.target.getAttribute('sector')+' sektoriaus');},
        fillColor: "#a1f7cc",
    }
);

function border_sector (id) {
    $('.sectors').removeClass('highlight');
    $('#'+id).addClass('highlight');
}


$('.hamburger-icon').click(function(){
    $('.navigation_menu_links').slideToggle();
    $('.hamburger-icon').toggleClass('active_mob_nav_icon');
});