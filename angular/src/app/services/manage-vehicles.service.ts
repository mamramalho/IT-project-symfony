import { HttpClient } from '@angular/common/http';
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
    return this.httpClient.post(this.baseUrl + '/vehicle/new', vehicle);
  }

  getVehicles(): Observable<any> {
    return this.httpClient.get(this.baseUrl + '/vehicle');
  }
}
