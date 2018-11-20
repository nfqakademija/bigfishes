export { Calendar as default}




class Calendar {

    constructor(){
        this.max_days_of_month = [31, 28, 31, 30 ,31, 30, 31, 31, 30, 31, 30, 31];
        this.sector_months = this.get_sector_months();
        this.sectors = {};
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
            'month': currentDate.month+1,
            'start_day': currentDate.day,
            'end_day': this.max_days_of_month[currentDate.month],
        };

        const secondMonth = {
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
        const sector_obj = {
            'first': {
                'name':"",
                'first_month': [],
                'second_month': []
            },
            'second': {
                'name':"",
                'first_month': [],
                'second_month': []
            },
            'third': {
                'name':"",
                'first_month': [],
                'second_month': []
            },
            'fourth': {
                'name':"",
                'first_month': [],
                'second_month': []
            },
            'fifth': {
                'name':"",
                'first_month': [],
                'second_month': []
            },
            'sixth': {
                'name':"",
                'first_month': [],
                'second_month': []
            },
            'seventh': {
                'name':"",
                'first_month': [],
                'second_month': []
            }
        }

        sector_obj.first.name = "Pirmas sektorius";
        sector_obj.second.name = "Antras sektorius";
        sector_obj.third.name = "Trecias sektorius";
        sector_obj.fourth.name = "Ketvirtas sektorius";
        sector_obj.fifth.name = "Penktas sektorius";
        sector_obj.sixth.name = "Sestas sektorius";
        sector_obj.seventh.name = "Septintas sektorius";

        for (const sector_key of Object.keys(sector_obj)) {
            for (let i = this.sector_months.first_month.start_day; i <= this.sector_months.first_month.end_day; i++) {
                sector_obj[sector_key].first_month.push({'day': i,
                                                         'month': this.sector_months.first_month.month,
                                                         'busy': false,
                                                         'start_from_8': false,
                                                         'start_from_20': false,
                                                         'registered_to':""});
            }
            for (let i = this.sector_months.second_month.start_day; i <= this.sector_months.second_month.end_day; i++) {
                sector_obj[sector_key].second_month.push({'day': i,
                                                          'month': this.sector_months.second_month.month,
                                                          'busy': false,
                                                          'start_from_8': false,
                                                          'start_from_20': false,
                                                          'registered_to':""});
            }
        }
        this.sectors = sector_obj;
    }


}




