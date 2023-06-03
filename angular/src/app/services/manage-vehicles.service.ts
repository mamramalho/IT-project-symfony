import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Vehicle } from '../models/vehicle';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ManageVehiclesService {

  private baseUrl = 'http://localhost:8888/api'

  constructor(private httpClient: HttpClient) { }

  addVehicle(vehicle: Vehicle): Observable<any> {
    const token = localStorage.getItem('access_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);

    return this.httpClient.post(this.baseUrl + '/vehicle/new', vehicle, { headers },);
  }

  getVehicles(): Observable<any> {
    return this.httpClient.get(this.baseUrl + '/vehicle');
  }
}
