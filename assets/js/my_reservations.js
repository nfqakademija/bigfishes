const user_reservations = JSON.parse(user_data.replace(/&quot;/g,'"'));
const table_head_names = ['ID','Pavadinimas','Nuo', 'Iki', 'Sektorius', 'Žuklės val.', 'Žvejų sk.', 'Kaina', 'Sukurta'];

if (user_reservations.length){
    for (let i = 0; i < table_head_names.length; i++){
        $('.ur_table_head_row').append('<th class="align-middle my_reservations_th-'+i+'">'+table_head_names[i]+'</th>');
    }


    for (let i = 0; i < user_reservations.length; i++){
        $('.tbody').append('<tr>' +
            '<td class="text-center">'+user_reservations[i].id+'</td>'+
            '<td class="ur_table_cell">'+user_reservations[i].reservation_name+'</td>'+
            '<td class="text-center">'+user_reservations[i].dateFrom+' '+user_reservations[i].timeFrom+':00'+'</td>'+
            '<td class="text-center">'+user_reservations[i].dateTo+' '+user_reservations[i].timeTo+':00'+'</td>'+
            '<td class="text-center">'+user_reservations[i].sectorName+'</td>'+
            '<td class="text-center">'+user_reservations[i].hours+'</td>'+
            '<td class="text-center">'+user_reservations[i].fishersNumber+'</td>'+
            '<td class="text-center">'+user_reservations[i].amount+'&euro;</td>'+
            '<td class="text-center">'+user_reservations[i].createdAt+'</td>'+
            '</tr>')
    }
}
else {
    $('.box-body').remove();
    $('.done_reservations').text('Jūs neturite rezervacijų')

}



