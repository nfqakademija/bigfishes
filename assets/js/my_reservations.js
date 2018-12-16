const user_reservations = JSON.parse(user_data.replace(/&quot;/g,'"'));
const table_head_names = ['Rez. pavadinimas','Nuo', 'Iki', 'Sektorius', 'Žuklės val.', 'Žvejų sk.', 'Kaina', 'Apmokėta'];

if (user_reservations.length){
    for (let i = 0; i < table_head_names.length; i++){
        $('.ur_head').append('<th class="ur_table_head_cell">'+table_head_names[i]+'</th>');
    }


    for (let i = 0; i < user_reservations.length; i++){
        $('.ur_table').append('<tr>' +
            '<td class="ur_table_cell">'+(i+1)+'</td>'+
            '<td class="ur_table_cell">'+user_reservations[i].reservation_name+'</td>'+
            '<td class="ur_table_cell">'+user_reservations[i].dateFrom+' '+user_reservations[i].timeFrom+':00'+'</td>'+
            '<td class="ur_table_cell">'+user_reservations[i].dateTo+' '+user_reservations[i].timeTo+':00'+'</td>'+
            '<td class="ur_table_cell">'+user_reservations[i].sectorName+'</td>'+
            '<td class="ur_table_cell">'+user_reservations[i].hours+'</td>'+
            '<td class="ur_table_cell">'+user_reservations[i].fishersNumber+'</td>'+
            '<td class="ur_table_cell">'+user_reservations[i].amount+'</td>'+
            '<td class="ur_table_cell">'+user_reservations[i].paymentStatus+'</td>'+
            '</tr>')
    }
}
else {
    $('.box-body').remove();
    $('.done_reservations').text('Jūs neturite rezervacijų')

}



