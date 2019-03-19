import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from 'environments/environment';

import { User } from '../_models';

@Injectable({ providedIn: 'root' })
export class UserService {
    constructor(private http: HttpClient) { }

    getAll() {
        return this.http.get<User[]>(`${environment.backOfficeUrl}/all`);
    }

    getById(id: number) {
        return this.http.get(`${environment.apiUrl}${environment.userUrl}/${id}`);
    }

    register(user: User) {
        return this.http.post(`${environment.apiUrl}${environment.userRegisterUrl}`, user);
    }

    update(user: User) {
        return this.http.put(`${environment.apiUrl}${environment.userUrl}/${user.id}`, user);
    }

    delete(id: number) {
        return this.http.delete(`${environment.apiUrl}${environment.userUrl}/${id}`);
    }
}