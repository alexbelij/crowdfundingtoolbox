import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {BehaviorSubject, Observable} from 'rxjs';
import 'rxjs/add/observable/empty';
import {TokenModel} from '../_models/token.model';
import {User} from '../_models';
import {environment} from 'environments/environment'
import * as jwt_decode from 'jwt-decode';

@Injectable({providedIn: 'root'})
export class AuthenticationService {
    public currentUser: Observable<User>;
    public currentUserSubject: BehaviorSubject<User>;

    constructor(private http: HttpClient) {
        this.currentUserSubject = new BehaviorSubject<User>(JSON.parse(localStorage.getItem('currentUser')));
        this.currentUser = this.currentUserSubject.asObservable();
    }

    public get currentUserValue(): User {
        return this.currentUserSubject.value;
    }

    public getToken(): string {
        const bearerToken = localStorage.getItem('token');
        if (!bearerToken) {
            return null;
        }
        return bearerToken;
    }

    getTokenExpirationDate(token: string): Date {
        const decoded = jwt_decode(token);

        if (decoded.exp === undefined) return null;

        const date = new Date(0);
        date.setUTCSeconds(decoded.exp);
        return date;
    }

    public getTokenExpirationNumber(token: string): number {
        return jwt_decode(token).exp;
    }

    isTokenExpired(token?: string): boolean {
        if (!token) token = this.getToken();
        if (!token) return true;

        const date = this.getTokenExpirationDate(token);
        if (date === undefined) return false;
        return !(date.valueOf() > new Date().valueOf());
    }

    public obtainNewToken(email: string,
                          password: string,
                          callback?: (token: TokenModel) => void,
                          onError?: (error) => void) {
        this.http.post<TokenModel>(environment.authServerUrl + environment.login, {
            'email': email,
            'password': password
        }).subscribe((response: TokenModel) => {
                localStorage.setItem('token', response.token);
                localStorage.setItem('user_firstName', response.user.first_name);
                localStorage.setItem('user_lastName', response.user.last_name);
                localStorage.setItem('user_role', response.user_role);
                callback(response);
            }, (error) => {
                onError(error);
            }
        );
    }

    public logout(onSuccess?: (data) => void, onError?: (error) => void): void {
        this.http.get(`${environment.authServerUrl}${environment.logout}`, {})
            .subscribe(data => {
                localStorage.removeItem('token');
                if (onSuccess) {
                    onSuccess(data);
                }
            }, error1 => {
                if (onError) {
                    onError(error1);
                }
            });
    }

    public stayLoggedIn() {
        this.http.get(`${environment.authServerUrl}${environment.refreshToken}`)
            .subscribe((response: TokenModel) => {
                localStorage.setItem('token', response.token);
            }, error1 => {
            });
    }

}
