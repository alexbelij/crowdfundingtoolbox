import {Component, Input, OnInit} from '@angular/core';
import {PaymentMethod} from '../../models/payment-method';

@Component({
    selector: 'app-payment-methods-list-item',
    templateUrl: './payment-methods-list-item.component.html',
    styleUrls: ['./payment-methods-list-item.component.scss']
})
export class PaymentMethodsListItemComponent implements OnInit {

    @Input()
    paymentMethod: PaymentMethod;

    extraClass: string = '';

    constructor() {
    }

    ngOnInit() {
        this.extraClass = 'inactive';
        if (this.paymentMethod.method_slug === 'bank-transfer' || this.paymentMethod.method_slug === 'pay-by-square') {
            this.extraClass = 'active';
        }
    }

    showPaymentOptions() {
        if (this.extraClass === 'active') {
            alert(this.paymentMethod.id)
        }
    }

}
