import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {NgbDateParserFormatter, NgbDateStruct} from "@ng-bootstrap/ng-bootstrap";
import {DateParserFormatter} from "./ngb-datepicker-formatter";
import {environment} from 'environments/environment';

@Component({
    selector: 'app-datepicker',
    templateUrl: './datepicker.component.html',
    styleUrls: ['../../../../sass/classes.scss', './datepicker.component.scss'],
    providers: [{provide: NgbDateParserFormatter, useClass: DateParserFormatter}]
})
export class DatepickerComponent implements OnInit {

    @Output()
    public dateChange = new EventEmitter<any>();
    @Input()
    public date;
    @Input()
    public display;
    @Input()
    public minDate;
    @Input()
    public maxDate;
    @Input()
    public type: string;

    public displayMonths = 1;
    public navigation = 'select';
    public showWeekNumbers = false;
    public outsideDays = 'visible';
    public environment = environment;


    ngOnInit(): void {

    }

    public changeEvent() {
        this.dateChange.emit(this.date);
    }

}

