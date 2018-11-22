export { Calendar as default}




class Calendar {

    constructor(sectors_information){
        this.max_days_of_month = [31, 28, 31, 30 ,31, 30, 31, 31, 30, 31, 30, 31];
        this.sector_months = this.get_sector_months();
        this.sectors = {};
        this.sectors_info = sectors_information;
    }

    get_sector_months () {
        const d = new Date();

        const currentDate = {
            year: d.getFullYear(),
            month: d.getMonth(),
            day: d.getDate()
        };
        const daysDiff = currentDate.day - 30;

        const firstMonth = {
            'year': currentDate.year,
            'month': currentDate.month+1,
            'start_day': currentDate.day,
            'end_day': this.max_days_of_month[currentDate.month],
        };

        const secondMonth = {
            'year': currentDate.year,
            'month': currentDate.month+2,
            'start_day': 1,
            'end_day': this.max_days_of_month[currentDate.month+1] + daysDiff - 2,
        };


        return this.sector_months = {
            'first_month': firstMonth,
            'second_month': secondMonth
        };
    }

    create_sectors () {
        const sector_obj = {};
        for (const sector_key of Object.keys(this.sectors_info)) {
            sector_obj[sector_key] = {
                'first_month': [],
                'second_month': []
            }
        }

        for (const sector_key of Object.keys(sector_obj)) {
            for (let i = this.sector_months.first_month.start_day; i <= this.sector_months.first_month.end_day; i++) {
                sector_obj[sector_key].first_month.push({'year': this.sector_months.first_month.year,
                                                         'month': this.sector_months.first_month.month,
                                                         'day': i,
                                                         'date': this.sector_months.first_month.year+'-'+this.sector_months.first_month.month+'-'+this.add_zero(i),
                                                         'start_date': '',
                                                         'start_from_8': false,
                                                         'start_from_20': false,
                                                         'user':'',
                                                         'reserved_till':'',
                                                         'end_to_8': false,
                                                         'end_to_20': false,
                                                         'busy': false
                                                        });
            }
            for (let u = this.sector_months.second_month.start_day; u <= this.sector_months.second_month.end_day; u++) {
                sector_obj[sector_key].second_month.push({'year': this.sector_months.second_month.year,
                                                          'month': this.sector_months.second_month.month,
                                                          'day': u,
                                                          'date': this.sector_months.second_month.year+'-'+this.sector_months.second_month.month+'-'+this.add_zero(u),
                                                          'start_date': '',
                                                          'start_from_8': false,
                                                          'start_from_20': false,
                                                          'user':'',
                                                          'reserved_till':'',
                                                          'end_to_8': false,
                                                          'end_to_20': false,
                                                          'busy': false
                                                         });
            }

        }
        this.sectors = sector_obj;
    }

    add_zero (number){
        if (number < 10) {
            return "0"+number;
        }
        else {
            return number;
        }
    }

}




