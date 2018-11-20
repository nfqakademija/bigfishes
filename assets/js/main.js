import Calendar from './class/Calendar.js';
import { check_cell_status,
         fill_busyness
       } from './calendar_editor.js';


const calendar = new Calendar();
calendar.create_sectors();
console.log(calendar);


const busy_date = {
    'sector': 'third',
    'month': 12,
    'day': 15,
    'busy': true
};

fill_busyness(busy_date, calendar);


for (let i = 0; i < calendar.sectors.first.first_month.length; i++){
    $('.table_head_row').append('<th class="table_head_cell">'+calendar.sectors.first.first_month[i].day+'</th>');
}

for (let i = 0; i < calendar.sectors.first.second_month.length; i++){
    $('.table_head_row').append('<th class="table_head_cell">'+calendar.sectors.first.second_month[i].day+'</th>');
}


for (const sectors_key of Object.keys(calendar.sectors)) {

    //appending sector name
    $('.calendar_table').append('<tr class="sectors" id="' + sectors_key + '">' +
                                    '<td class="sectors_cell">' + calendar.sectors[sectors_key].name + '</td>' +
                                '</tr>');
    //appending first month
    for (let i = 0; i < calendar.sectors[sectors_key].first_month.length; i++) {
        $('#'+sectors_key).append('<td class="sectors_day_cell '+check_cell_status(calendar.sectors[sectors_key].first_month[i])+'"></td>');
    }
    //appending second month
    for (let i = 0; i < calendar.sectors[sectors_key].second_month.length; i++) {
        $('#'+sectors_key).append('<td class="sectors_day_cell '+check_cell_status(calendar.sectors[sectors_key].second_month[i])+'"></td>');
    }
}




