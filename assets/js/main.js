import Calendar from './class/Calendar.js';
import { check_cell_status,
         fill_busyness,
       } from './calendar_editor.js';





const ajax_call_response = '{"Pirmas_sektorius":{"date0":{"start_date":"2018-12-01","start_from_8":true,"start_from_20":false,"user":"Dainius","reserved_till":"2018-12-02","end_to_8":true,"end_to_20":false},"date1":{"start_date":"2018-12-05","start_from_8":false,"start_from_20":true,"user":"Laimutis","reserved_till":"2018-12-07","end_to_8":false,"end_to_20":true}},"Antras_sektorius":[],"Trecias_sektorius":[],"Ketvirtas_sektorius":[],"Penktas_sektorius":[],"Sestas_sektorius":{"date0":{"start_date":"2018-11-05","start_from_8":true,"start_from_20":false,"user":"Dainius","reserved_till":"2018-12-09","end_to_8":true,"end_to_20":false}},"Septintas_sektorius":[]}';
const sectors_information = JSON.parse(ajax_call_response);


const calendar = new Calendar(sectors_information);
calendar.create_sectors();
console.log(calendar);
fill_busyness(sectors_information, calendar);






for (let i = 0; i < calendar.sectors[Object.keys(calendar.sectors)[0]].first_month.length; i++){
    $('.table_head_row').append('<th class="table_head_cell">'+calendar.sectors[Object.keys(calendar.sectors)[0]].first_month[i].day+'</th>');
}

for (let i = 0; i < calendar.sectors[Object.keys(calendar.sectors)[0]].second_month.length; i++){
    $('.table_head_row').append('<th class="table_head_cell">'+calendar.sectors[Object.keys(calendar.sectors)[0]].second_month[i].day+'</th>');
}


for (const sectors_key of Object.keys(calendar.sectors)) {

    //appending sector name
    $('.calendar_table').append('<tr class="sectors" id="' + sectors_key + '">' +
                                    '<td class="sectors_cell">' + sectors_key.replace("_", " ") + '</td>' +
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


