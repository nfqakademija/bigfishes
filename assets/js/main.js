import {isWithinRange, isBefore, addDays, format, endOfMonth, getMonth} from 'date-fns';

const kk = json_content.replace(/&quot;/g,'\\"').slice(1,-2)+'"';
const jj = JSON.parse(JSON.parse(kk));
console.log(jj);



const STATUS_BUSY_FIRST = "busy-start_from_20";
const STATUS_BUSY_SECOND = "busy-start_from_8";
const STATUS_BUSY = "busy";
const STATUS_FREE = "free";

const monthNames = ["Sausio",
                    "Vasario",
                    "Kovo",
                    "Balandžio",
                    "Gegužės",
                    "Birželio",
                    "Liepos",
                    "Rugpjūčio",
                    "Rugsėjo",
                    "Spalio",
                    "Lapkričio",
                    "Gruodžio"];


const today = new Date();
const daysTo = 30;
const renderDays = [];
const dates = [];

for (let day = new Date(); isBefore(day, addDays(today, daysTo)); day = addDays(day, 1)) {
    dates.push({
        'date': format(day, 'YYYY-MM-DD'),
        'dayNumber': format(day, 'D'),
        'dayOfWeek': format(day, 'ddd')
    });
}

$('.from_to_merged').text('Nuo ' + monthNames[today.getMonth()] + ' mėn. ' + format(today, 'D') + ' d. iki ' + monthNames[getMonth(addDays(today, daysTo - 1))] + ' mėn. ' + format(addDays(today, daysTo - 1), 'D') + ' d.');

for (let i = 0; i < dates.length; i++) {
    $('.table_head_row').append('<th class="table_head_cell ' + dates[i].dayOfWeek + '">' + dates[i].dayNumber + '</th>');
}


for (const sector in jj) {
    $('.calendar_table').append('<tr class="sectors" id="' + sector + '">' +
        '<td class="sectors_cell">' + jj[sector].name.replace("Sektorius", "") + '</td>');
    for (let i = 0; i < dates.length; i++) {
        let dayInfo = STATUS_FREE;
        let name = "";
        let timeTo = "";
        for (const reservationId in jj[sector].reservation_dates) {
            const reservation = jj[sector].reservation_dates[reservationId];
            const isInRange = isWithinRange(dates[i].date, reservation.dateFrom, reservation.dateTo);

            if (isInRange && dates[i].date === reservation.dateFrom && reservation.timeFrom === '08' && Object.keys(jj[sector].reservation_dates).find(
                function (v) {
                    if (isWithinRange(dates[i].date, jj[sector].reservation_dates[v].dateFrom, jj[sector].reservation_dates[v].dateTo) && jj[sector].reservation_dates[v].dateFrom === reservation.dateFrom && jj[sector].reservation_dates[v].timeFrom === "20") {
                        return true;
                    }
                })) {
                dayInfo = STATUS_BUSY;
                name = reservation.name;
                timeTo = reservation.timeTo;
            } else if (isInRange && dates[i].date === reservation.dateFrom && reservation.dateFrom === reservation.dateTo && reservation.timeFrom === '08') {
                dayInfo = STATUS_BUSY_SECOND;
                name = reservation.name;
                timeTo = reservation.timeTo;
            } else if (isInRange && dates[i].date === reservation.dateFrom && reservation.timeFrom === '08') {
                dayInfo = STATUS_BUSY;
                name = reservation.name;
                timeTo = reservation.timeTo;
            } else if (isInRange && dates[i].date === reservation.dateFrom && reservation.timeFrom === '20' && Object.keys(jj[sector].reservation_dates).find(
                function (v) {
                    if (isWithinRange(dates[i].date, jj[sector].reservation_dates[v].dateFrom, jj[sector].reservation_dates[v].dateTo) && jj[sector].reservation_dates[v].dateTo === reservation.dateFrom && jj[sector].reservation_dates[v].timeTo === "20") {
                        return true;
                    }
                })) {
                dayInfo = STATUS_BUSY;
                name = reservation.name;
                timeTo = reservation.timeTo;
            } else if (isInRange && dates[i].date === reservation.dateFrom && reservation.timeFrom === '20') {
                dayInfo = STATUS_BUSY_FIRST;
                name = reservation.name;
                timeTo = reservation.timeTo;
            } else if (isInRange && dates[i].date === reservation.dateTo && reservation.timeTo === '20' && Object.keys(jj[sector].reservation_dates).find(
                function (v) {
                    if (isWithinRange(dates[i].date, jj[sector].reservation_dates[v].dateFrom, jj[sector].reservation_dates[v].dateTo) && jj[sector].reservation_dates[v].dateFrom === reservation.dateTo && jj[sector].reservation_dates[v].timeFrom === "20") {
                        return true;
                    }
                })) {
                dayInfo = STATUS_BUSY;
                name = reservation.name;
                timeTo = reservation.timeTo;
            } else if (isInRange && dates[i].date === reservation.dateTo && reservation.timeTo === '20') {
                dayInfo = STATUS_BUSY_SECOND;
                name = reservation.name;
                timeTo = reservation.timeTo;
            } else if (isInRange && dates[i].date !== reservation.dateFrom && dates[i].date !== reservation.dateTo) {
                dayInfo = STATUS_BUSY;
                name = reservation.name;
                timeTo = reservation.timeTo;
            }
        }
        $('#' + [sector]).append('<td class="sectors_day_cell '+ dayInfo +' ' + dates[i].dayOfWeek + '" title="' + name + '"  date="' + dates[i].date + '" sector=' + sector + '" onclick="location.href=\'/reservation?date=' + dates[i].date + '&sector_name=' + jj[sector].name + '&timeTo=' + timeTo + '\'"></td>');

        renderDays.push({
            'sector': sector,
            'date': dates[i].date,
            'dayOfWeek': dates[i].dayOfWeek,
            'status': dayInfo,
            'name': name
        })
    }
}


$('.busy').removeAttr("onclick");

$('.sectors_day_cell').not('.busy').css('cursor', 'pointer');

$('.sectors_day_cell').not('.busy').hover(function () {
    $(this).toggleClass('highlight_cell')
})






































