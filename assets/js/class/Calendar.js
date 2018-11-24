export { Calendar as default}




class Calendar {

    constructor(sectors_information) {
        this.max_days_of_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        this.sectors_info = sectors_information;
        this.months = this.create_months();
        this.sector_dates = this.create_dates();
        this.sector_names = this.get_sector_names();
        this.sectors = this.create_sectors ();
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


    get_sector_names () {
        const sector_names = [];
        for (const sector_key of Object.keys(this.sectors_info)) {
            sector_names.push(this.sectors_info[sector_key].name);
        }
        return sector_names;
    }

    create_sectors () {
        const sec_obj = this.add_names_to_sector_obj ();
        const sector_cells_with_dates = this.create_sector_cells_with_dates(sec_obj);
        return sector_cells_with_dates;
    }

    add_names_to_sector_obj () {
        const obj = {};
        for (let i = 0; i < this.sector_names.length; i++) {
            obj['sector' + (i + 1)] = {
                'name': this.sector_names[i],
                'cells': {}
            }
        }
        return obj;
    }

    create_sector_cells_with_dates (obj) {
        for (const sector of Object.keys(obj)) {
            for (let i = 0; i < this.sector_dates.length; i++)
                obj[sector].cells['cell' + i] = {
                                            'date': this.sector_dates[i],
                                            'start_date': '',
                                            'start_from_8': false,
                                            'start_from_20': false,
                                            'user':'',
                                            'reserved_till':'',
                                            'end_to_8': false,
                                            'end_to_20': false,
                                            'busy': false
            }
        }
        return obj;
    }
}
























//         for (const sector_key of Object.keys(sector_obj)) {
//             for (let i = this.sector_months.first_month.start_day; i <= this.sector_months.first_month.end_day; i++) {
//                 sector_obj[sector_key].first_month.push({'year': this.sector_months.first_month.year,
//                                                          'month': this.sector_months.first_month.month,
//                                                          'day': i,
//                                                          'date': this.sector_months.first_month.year+'-'+this.sector_months.first_month.month+'-'+this.add_zero(i),
//                                                          'start_date': '',
//                                                          'start_from_8': false,
//                                                          'start_from_20': false,
//                                                          'user':'',
//                                                          'reserved_till':'',
//                                                          'end_to_8': false,
//                                                          'end_to_20': false,
//                                                          'busy': false
//                                                         });
//             }
//             for (let u = this.sector_months.second_month.start_day; u <= this.sector_months.second_month.end_day; u++) {
//                 sector_obj[sector_key].second_month.push({'year': this.sector_months.second_month.year,
//                                                           'month': this.sector_months.second_month.month,
//                                                           'day': u,
//                                                           'date': this.sector_months.second_month.year+'-'+this.sector_months.second_month.month+'-'+this.add_zero(u),
//                                                           'start_date': '',
//                                                           'start_from_8': false,
//                                                           'start_from_20': false,
//                                                           'user':'',
//                                                           'reserved_till':'',
//                                                           'end_to_8': false,
//                                                           'end_to_20': false,
//                                                           'busy': false
//                                                          });
//             }
//
//         }
//         this.sectors = sector_obj;
//     }
//
//     add_zero (number){
//         if (number < 10) {
//             return "0"+number;
//         }
//         else {
//             return number;
//         }
//     }
//
// }




