import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../../../environments/environment';
import {HelpersService} from './helpers.service';

@Injectable({
    providedIn: 'root'
})
export class ExportCsvService {

    constructor(private http: HttpClient, private helperService: HelpersService) {
    }

    public makeExport(title: string, exportType: string, from: string, to: string) {
        return this.http.post(`${environment.backOfficeUrl}${environment.exportCsv}`, {
            title: title,
            from: this.helperService.writeDateAsString(from),
            to: this.helperService.writeDateAsString(to),
            type: exportType
        }, {
            responseType: 'blob'
        });
    }
}
