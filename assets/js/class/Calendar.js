export { Calendar as default};





class Calendar {
    constructor(sectors_information) {
        this.max_days_of_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        this.sectors_info = this.add_between_dates(sectors_information);
        this.months = this.create_months();
        this.sector_dates = this.create_dates();
        this.sector_names = this.get_sector_names();
        this.sectors = this.create_sectors();
    }

    create_months() {
        const d = new Date();

        const currentDate = {
            year: d.getFullYear(),
            month: d.getMonth(),
            day: d.getDate()
        };
        const daysDiff = currentDate.day - 30;

        const firstMonth = {
            'year': currentDate.year,
            'month': currentDate.month + 1,
            'start_day': currentDate.day,
            'end_day': this.max_days_of_month[currentDate.month],
        };

        const secondMonth = {
            'year': currentDate.year,
            'month': currentDate.month + 2,
            'start_day': 1,
            'end_day': this.max_days_of_month[currentDate.month + 1] + daysDiff - 2,
        };


        const months = {
            'first_month': firstMonth,
            'second_month': secondMonth
        };
        return months;
    }

    create_dates() {
        const start_date = this.months.first_month.year + '-' + this.months.first_month.month + '-' + this.months.first_month.start_day;
        const end_date = this.months.second_month.year + '-' + this.months.second_month.month + '-' + this.months.second_month.end_day;
        const cells = this.fix_dates(this.calculate_between_dates(new Date(start_date), new Date(end_date)));
        return cells;
    }

    calculate_between_dates(start_date, end_date) {
        for (var arr = [], dt = start_date; dt <= end_date; dt.setDate(dt.getDate() + 1)) {
            arr.push(new Date(dt));
        }
        return arr;
    }

    fix_dates(date_arr) {
        const fixed_dates = [];
        for (let i = 0; i < date_arr.length; i++) {
            fixed_dates.push(date_arr[i].toISOString().slice(0, 10));
        }
        return fixed_dates;
    }


    get_sector_names() {
        const sector_names = [];
        for (const sector_key of Object.keys(this.sectors_info)) {
            sector_names.push(this.sectors_info[sector_key].name);
        }
        return sector_names;
    }

    create_sectors() {
        const sec_obj = this.add_names_to_sector_obj();
        const sector_cells_with_dates = this.create_sector_cells_with_dates(sec_obj);
        this.create_sector_cells_with_busy_info(sector_cells_with_dates);
        this.check_whole_day_business(sector_cells_with_dates);
        return sector_cells_with_dates;
    }

    add_names_to_sector_obj() {
        const obj = {};
        for (let i = 0; i < this.sector_names.length; i++) {
            obj['sector' + (i + 1)] = {
                'name': this.sector_names[i],
                'cells': {}
            }
        }
        return obj;
    }

    create_sector_cells_with_dates(obj) {
        for (const sector of Object.keys(obj)) {
            for (let i = 0; i < this.sector_dates.length; i++)
                obj[sector].cells['cell' + i] = {
                    'date': this.sector_dates[i],
                    'start_date': '',
                    'start_from_8': false,
                    'start_from_20': false,
                    'user': '',
                    'reserved_till': '',
                    'end_to_8': false,
                    'end_to_20': false,
                    'busy': false
                }
        }
        return obj;
    }

    create_sector_cells_with_busy_info(sectors_obj){
        for (const sector of Object.keys(sectors_obj)){
            for (const busy_sector of Object.keys(this.sectors_info)){
                if (sectors_obj[sector].name == this.sectors_info[busy_sector].name && this.sectors_info[busy_sector].reservation_dates.hasOwnProperty('date0')){
                    this.fill_gaps(sectors_obj[sector], this.sectors_info[busy_sector]);
                    for (const date of Object.keys(this.sectors_info[busy_sector].reservation_dates)) {
                        for (const cell of Object.keys(sectors_obj[sector].cells)){
                            if(sectors_obj[sector].cells[cell].date == this.sectors_info[busy_sector].reservation_dates[date].start_date) {
                                sectors_obj[sector].cells[cell].start_date = this.sectors_info[busy_sector].reservation_dates[date].start_date;
                                sectors_obj[sector].cells[cell].start_from_8 = this.sectors_info[busy_sector].reservation_dates[date].start_from_8;
                                sectors_obj[sector].cells[cell].start_from_20 = this.sectors_info[busy_sector].reservation_dates[date].start_from_20;
                                sectors_obj[sector].cells[cell].reserved_till = this.sectors_info[busy_sector].reservation_dates[date].reserved_till;
                                sectors_obj[sector].cells[cell].user = this.sectors_info[busy_sector].reservation_dates[date].user;
                            }
                            else if (sectors_obj[sector].cells[cell].date == this.sectors_info[busy_sector].reservation_dates[date].reserved_till){
                                sectors_obj[sector].cells[cell].reserved_till = this.sectors_info[busy_sector].reservation_dates[date].reserved_till;
                                sectors_obj[sector].cells[cell].end_to_8 = this.sectors_info[busy_sector].reservation_dates[date].end_to_8;
                                sectors_obj[sector].cells[cell].end_to_20 = this.sectors_info[busy_sector].reservation_dates[date].end_to_20;
                            }
                        }
                    }
                }
            }
        }
    }

    fill_gaps (cal_sector_obj, busy_sector_obj){
        for (const date of Object.keys(busy_sector_obj.reservation_dates)){
            for (let i = 0; i < busy_sector_obj.reservation_dates[date].dates_between.length; i++){
                for (const cell of Object.keys(cal_sector_obj.cells)){
                    if(busy_sector_obj.reservation_dates[date].dates_between[i] == cal_sector_obj.cells[cell].date){
                        cal_sector_obj.cells[cell].busy = true;
                    }
                }
            }
        }
    }

    check_whole_day_business (cal_sec_obj) {
        for (const sector of Object.keys(cal_sec_obj)){
            for (const cell of Object.keys(cal_sec_obj[sector].cells)){
                if(cal_sec_obj[sector].cells[cell].date.slice(5, 7) == cal_sec_obj[sector].cells[cell].start_date.slice(5, 7) && cal_sec_obj[sector].cells[cell].start_date.slice(8, 10) == (parseInt(cal_sec_obj[sector].cells[cell].reserved_till.slice(8,10))-1))
                {
                    cal_sec_obj[sector].cells[cell].busy = true;
                }
            }
        }
    }

    add_between_dates(busy_obj) {
        for (const cell of Object.keys(busy_obj)) {
            for (const date of Object.keys(busy_obj[cell].reservation_dates)) {
                if (busy_obj[cell].reservation_dates[date].start_date) {
                    let dates_between__arr = this.fix_dates(this.calculate_between_dates(new Date(busy_obj[cell].reservation_dates[date].start_date), new Date(busy_obj[cell].reservation_dates[date].reserved_till)));
                    let dates_between_arr = this.cut_start_end_element(dates_between__arr, busy_obj[cell].reservation_dates[date]);
                    busy_obj[cell].reservation_dates[date]['dates_between'] = dates_between_arr;
                }
            }
        }
        return busy_obj;
    }

    cut_start_end_element(dates_arr, busy_cell) {
        let cuted_start = dates_arr.filter(e => e !== busy_cell.start_date);
        let cuted_end = cuted_start.filter(e => e !== busy_cell.reserved_till);
        return cuted_end;
    }


}





