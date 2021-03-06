import {Donation} from './donation';

export class Payment {
    id: number;
    transaction_id: string;
    donation: Donation;
    iban: string;
    variable_symbol: number;
    amount: number;
    created_by: string;
    transaction_date: string;
    transfer_type: number;
    status: string;
    payment_notes: string;
}
