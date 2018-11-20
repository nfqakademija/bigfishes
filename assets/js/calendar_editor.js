export function check_cell_status (obj) {
    if(obj.busy){
        return "busy";
    }
    else{
        return "free";
    }
}

export function fill_busyness (fill_obj, calendar) {
    for (const sector_key in calendar.sectors) {
        if (sector_key == fill_obj.sector){
            for(let i = 0; i < calendar.sectors[sector_key].first_month.length; i++){
                if (sector_key == fill_obj.sector && fill_obj.month == calendar.sectors[sector_key].first_month[i].month && fill_obj.day == calendar.sectors[sector_key].first_month[i].day){
                    calendar.sectors[sector_key].first_month[i].busy = fill_obj.busy;
                }
            }
            for(let i = 0; i < calendar.sectors[sector_key].second_month.length; i++){
                if (sector_key == fill_obj.sector && fill_obj.month == calendar.sectors[sector_key].second_month[i].month && fill_obj.day == calendar.sectors[sector_key].second_month[i].day){
                    calendar.sectors[sector_key].second_month[i].busy = fill_obj.busy;
                }
            }
        }
    }
}
