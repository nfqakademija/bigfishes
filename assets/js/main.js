import {isWithinRange, isBefore, addDays, format} from 'date-fns';

const jj = JSON.parse(JSON.parse(document.getElementsByClassName("json_info")[0].getAttribute("data-calendar_information")));
console.log(jj);


const STATUS_BUSY_FIRST = "busy-start_from_20";
const STATUS_BUSY_SECOND = "busy-start_from_8";
const STATUS_BUSY = "busy";
const STATUS_FREE = "free";


const today = new Date();
const renderDays = [];
const dates = [];

for (let day = new Date(); isBefore(day, addDays(today, 30)); day = addDays(day, 1)) {
    dates.push({
        'date': format(day, 'YYYY-MM-DD'),
        'dayNumber': format(day, 'DD'),
        'dayOfWeek': format(day, 'ddd')
    });
}

for (let i = 0; i < dates.length; i++) {
    $('.table_head_row').append('<th class="table_head_cell ' + dates[i].dayOfWeek + '">' + dates[i].dayNumber + '</th>');
}


for (const sector in jj) {
    $('.calendar_table').append('<tr class="sectors" id="' + sector + '">' +
        '<td class="sectors_cell">' + jj[sector].name + '</td>');
    for (let i = 0; i < dates.length; i++) {
        let dayInfo = STATUS_FREE;
        let name = "";
        let timeFrom = "";
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
                timeFrom = reservation.timeFrom;
            } else if (isInRange && dates[i].date === reservation.dateFrom && reservation.dateFrom === reservation.dateTo && reservation.timeFrom === '08') {
                dayInfo = STATUS_BUSY_SECOND;
                name = reservation.name;
                timeFrom = reservation.timeFrom;
            } else if (isInRange && dates[i].date === reservation.dateFrom && reservation.timeFrom === '08') {
                dayInfo = STATUS_BUSY;
                name = reservation.name;
                timeFrom = reservation.timeFrom;
            } else if (isInRange && dates[i].date === reservation.dateFrom && reservation.timeFrom === '20' && Object.keys(jj[sector].reservation_dates).find(
                function (v) {
                    if (isWithinRange(dates[i].date, jj[sector].reservation_dates[v].dateFrom, jj[sector].reservation_dates[v].dateTo) && jj[sector].reservation_dates[v].dateTo === reservation.dateFrom && jj[sector].reservation_dates[v].timeTo === "20") {
                        return true;
                    }
                })) {
                dayInfo = STATUS_BUSY;
                name = reservation.name;
                timeFrom = reservation.timeFrom;
            } else if (isInRange && dates[i].date === reservation.dateFrom && reservation.timeFrom === '20') {
                dayInfo = STATUS_BUSY_FIRST;
                name = reservation.name;
                timeFrom = reservation.timeFrom;
            } else if (isInRange && dates[i].date === reservation.dateTo && reservation.timeTo === '20' && Object.keys(jj[sector].reservation_dates).find(
                function (v) {
                    if (isWithinRange(dates[i].date, jj[sector].reservation_dates[v].dateFrom, jj[sector].reservation_dates[v].dateTo) && jj[sector].reservation_dates[v].dateFrom === reservation.dateTo && jj[sector].reservation_dates[v].timeFrom === "20") {
                        return true;
                    }
                })) {
                dayInfo = STATUS_BUSY;
                name = reservation.name;
                timeFrom = reservation.timeFrom;
            } else if (isInRange && dates[i].date === reservation.dateTo && reservation.timeTo === '20') {
                dayInfo = STATUS_BUSY_SECOND;
                name = reservation.name;
                timeFrom = reservation.timeFrom;
            } else if (isInRange && dates[i].date !== reservation.dateFrom && dates[i].date !== reservation.dateTo) {
                dayInfo = STATUS_BUSY;
                name = reservation.name;
                timeFrom = reservation.timeFrom;
            }
        }
        $('#' + [sector]).append('<td class="sectors_day_cell ' + dayInfo + ' ' + dates[i].dayOfWeek + '" title="' + name + '"  date="' + dates[i].date + '" sector=' + sector + '" onclick="location.href=\'/reservation?date=' + dates[i].date + '&sector_name=' + jj[sector].name + '&timeFrom=' + timeFrom + '\'"></td>');

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






































