import Calendar from './class/Calendar.js';
import { check_cell_status } from './calendar_editor.js';


const ajax_call_response = '{"sector1":\n' +
    '    {\n' +
    '     "name": "Pirmas sektorius",\n' +
    '     "reservation_dates":\n' +
    '\t                      {"date0":\n' +
    '\t\t                              {\n' +
    '\t\t                                "start_date":"2018-11-01",\n' +
    '         \t                         "start_from_8":true,\n' +
    '\t                                  "start_from_20":false,\n' +
    '                                    "user":"Dainius",\n' +
    '\t                                  "reserved_till":"2018-12-02",\n' +
    '        \t                          "end_to_8":true,\n' +
    '        \t                          "end_to_20":false\n' +
    '\t\t                              },\n' +
    '                         "date1":\n' +
    '\t\t                              {\n' +
    '\t\t                                "start_date":"2018-12-05",\n' +
    '\t                                  "start_from_8":false,\n' +
    '\t                                  "start_from_20":true,\n' +
    '        \t                          "user":"Laimutis",\n' +
    '        \t                          "reserved_till":"2018-12-07",\n' +
    '        \t                          "end_to_8":false,\n' +
    '        \t                          "end_to_20":true\n' +
    '                                   }\n' +
    '\t                      }\n' +
    '    },\n' +
    ' "sector2":\n' +
    '    {\n' +
    '     "name": "Antras sektorius",\n' +
    '     "reservation_dates": {} \n' +
    '    },\n' +
    ' "sector3":\n' +
    '    {\n' +
    '     "name": "Trecias sektorius",\n' +
    '     "reservation_dates": {} \n' +
    '    },\n' +
    ' "sector4":\n' +
    '    {\n' +
    '     "name": "Ketvirtas sektorius",\n' +
    '     "reservation_dates": \n' +
    '                        {"date0":\n' +
    '\t\t                              {\n' +
    '\t\t                                "start_date":"2018-11-28",\n' +
    '\t                                  "start_from_8":false,\n' +
    '\t                                  "start_from_20":true,\n' +
    '        \t                          "user":"Anzelmas",\n' +
    '        \t                          "reserved_till":"2018-12-11",\n' +
    '        \t                          "end_to_8":false,\n' +
    '        \t                          "end_to_20":true\n' +
    '                                   }\n' +
    '                          \n' +
    '                        } \n' +
    '    },\n' +
    ' "sector5":\n' +
    '    {\n' +
    '     "name": "Penktas sektorius",\n' +
    '     "reservation_dates": {} \n' +
    '    },\n' +
    ' "sector6":\n' +
    '    {\n' +
    '     "name": "Sestas sektorius",\n' +
    '     "reservation_dates": {} \n' +
    '    },\n' +
    ' "sector7":\n' +
    '    {\n' +
    '     "name": "Septintas sektorius",\n' +
    '     "reservation_dates": {} \n' +
    '    }\n' +
    '}';
const sectors_information = JSON.parse(ajax_call_response);


const calendar = new Calendar(sectors_information);

for (let i = 0; i < calendar.sector_dates.length; i++)
{
    $('.table_head_row').append('<th class="table_head_cell">'+new Date(calendar.sector_dates[i]).toISOString().slice(8, 10)+'</th>');
}

for (let i = 0; i < calendar.sector_names.length; i++) {
    $('.calendar_table').append('<tr class="sectors" id="sector' + (i+1) + '">' + '<td class="sectors_cell">' + calendar.sector_names[i] + '</td></tr>');
}


for (const sector of Object.keys(calendar.sectors)) {
    for (const cell of Object.keys(calendar.sectors[sector].cells))
        $('#'+[sector]).append('<td class="sectors_day_cell '+check_cell_status((calendar.sectors[sector].cells[cell]))+'"><a href="/reservation?date='+calendar.sectors[sector].cells[cell].date+'?sector_name='+calendar.sectors[sector].name+'" style="display:block;">&nbsp;</a></td>');
}









































