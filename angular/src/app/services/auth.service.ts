import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, map } from 'rxjs';
import { User } from '../models/user';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private baseUrl = 'http://localhost:8888/api'

  constructor(private httpClient: HttpClient) { }

  login(credentials: any): Observable<any> {
    return this.httpClient.post(this.baseUrl+'/login_check', credentials)
      .pipe(
        map((response: any) => {
          const token = response.token;
          console.log('FUCKING TOKEN' + token);
          // Store the token in localStorage
          localStorage.setItem('access_token', token);
          return response;
        })
      );
  }

  logout(): void {
    // Remove the token from localStorage
    localStorage.removeItem('access_token');
  }

  isLoggedIn() {
    // Check if the token is present in localStorage
    return localStorage.getItem('access_token') !== null;
  }

  register(user: User): Observable<any> {
    return this.httpClient.post(this.baseUrl+'/register', user);
  }

}
