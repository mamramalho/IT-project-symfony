import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';


@Injectable({
  providedIn: 'root'
})
export class ManageUsersService {

  private baseUrl = 'http://localhost:8888/api/users'

  constructor(private httpClient: HttpClient) { }

  getCurrentUser(): Observable<any> {
    return this.httpClient.get(`${this.baseUrl}/current`);
  }

  getUser(id: number): Observable<any> {
    return this.httpClient.get(`${this.baseUrl}/${id}`);
  }

  getUserByEmail(email: string): Observable<any> {
    return this.httpClient.get(`${this.baseUrl}/email/${email}`);
  }
}
