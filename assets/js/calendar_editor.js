export function check_cell_status (obj) {
    if(obj.start_from_8 || obj.start_from_20){
        return "busy-start";
    }
    else if(obj.end_to_8 || obj.end_to_20){
        return "busy-end";
    }
    else if(obj.busy){
        return "busy";
    }
    else{
        return "free";
    }
}



export function fill_busyness(busy_obj, calendar) {
    for (const sectors_key of Object.keys(busy_obj)) {
        if (check_sector_busyness(busy_obj[sectors_key])) {
            for (const calendar_sector_name of Object.keys(calendar.sectors)) {
                if (calendar_sector_name == sectors_key) {
                    fill_start_and_end(busy_obj[sectors_key], calendar.sectors[calendar_sector_name]);
                    fill_busy_gaps(busy_obj[sectors_key], calendar.sectors[calendar_sector_name]);
                }
            }
        }
    }
}






function check_sector_busyness (obj) {
    if(Object.keys(obj)[0]){
        return true;
    }
    else {
        return false;
    }
}

function fill_start_and_end (busy_obj, calendar_obj){
    for (let i = 0; i < calendar_obj.first_month.length; i++) {
        for (const busy_date_key of Object.keys(busy_obj)) {
            if (busy_obj[busy_date_key].start_date == calendar_obj.first_month[i].date) {
                calendar_obj.first_month[i].start_date = busy_obj[busy_date_key].start_date;
                calendar_obj.first_month[i].start_from_8 = busy_obj[busy_date_key].start_from_8;
                calendar_obj.first_month[i].start_from_20 = busy_obj[busy_date_key].start_from_20;
                calendar_obj.first_month[i].user = busy_obj[busy_date_key].user;
            }
            else if (busy_obj[busy_date_key].registered_till == calendar_obj.first_month[i].date) {
                calendar_obj.first_month[i].reserved_till = busy_obj[busy_date_key].reserved_till;
                calendar_obj.first_month[i].end_to_8 = busy_obj[busy_date_key].end_to_8;
                calendar_obj.first_month[i].end_to_20 = busy_obj[busy_date_key].end_to_8;
                calendar_obj.first_month[i].user = busy_obj[busy_date_key].user;
            }
        }

    }
    for (let i = 0; i < calendar_obj.second_month.length; i++) {
        for (const busy_date_key of Object.keys(busy_obj)) {
            if (busy_obj[busy_date_key].start_date == calendar_obj.second_month[i].date) {
                calendar_obj.second_month[i].start_date = busy_obj[busy_date_key].start_date;
                calendar_obj.second_month[i].start_from_8 = busy_obj[busy_date_key].start_from_8;
                calendar_obj.second_month[i].start_from_20 = busy_obj[busy_date_key].start_from_20;
                calendar_obj.second_month[i].user = busy_obj[busy_date_key].user;
            }
            else if (busy_obj[busy_date_key].reserved_till == calendar_obj.second_month[i].date) {
                calendar_obj.second_month[i].reserved_till = busy_obj[busy_date_key].reserved_till;
                calendar_obj.second_month[i].end_to_8 = busy_obj[busy_date_key].end_to_8;
                calendar_obj.second_month[i].end_to_20 = busy_obj[busy_date_key].end_to_20;
                calendar_obj.second_month[i].user = busy_obj[busy_date_key].user;
            }
        }
    }
}



function fill_busy_gaps(busy_dates, calendar_sector) {
    let busy_days = [];

    //create busy_days array
    for (const date_key of Object.keys(busy_dates)){
        let dates = calculate_between_dates(new Date(busy_dates[date_key].start_date), new Date (busy_dates[date_key].reserved_till));
            for (let i = 0; i < dates.length; i++) {
                busy_days.push(dates[i].toISOString().slice(0,10));
        }
    }

    //eliminate start and end dates
    for (const date_key of Object.keys(busy_dates)){
        //eliminte start dates
        for(let i in busy_days){
            if(busy_days[i]==busy_dates[date_key].start_date){
                busy_days.splice(i,1);
            }
        }
        //eliminte end_dates
        for(let i in busy_days){
            if(busy_days[i]==busy_dates[date_key].reserved_till){
                busy_days.splice(i,1);
            }
        }
    }
    //edit_calendar_object
    for (const months of Object.keys(calendar_sector)){
        for (let i = 0; i < calendar_sector[months].length; i++) {
            for (let u = 0; u < busy_days.length; u++){
                if (busy_days[u] == calendar_sector[months][i].date) {
                    calendar_sector[months][i].busy = true;
                }
            }
        }
    }
}

function calculate_between_dates(start_date, end_date) {
    for(var arr=[],dt=start_date; dt<=end_date; dt.setDate(dt.getDate()+1)){
        arr.push(new Date(dt));
    }
    return arr;
}