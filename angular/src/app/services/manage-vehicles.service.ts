import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Vehicle } from '../models/vehicle';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ManageVehiclesService {
  
  private baseUrl = 'http://localhost:8888/api/vehicle'

  constructor(private httpClient: HttpClient) { }

  addVehicle(vehicle: Vehicle): Observable<any> {
    const token = localStorage.getItem('access_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);

    return this.httpClient.post(this.baseUrl + '/new', vehicle, { headers },);
  }

  getVehicles(): Observable<any> {
    return this.httpClient.get(this.baseUrl + '/');
  }

  getVehicle(vehicleId: number): Observable<any> {
    return this.httpClient.get(`${this.baseUrl}/${vehicleId}`);
  }

  search(text: string|null, city: string|null): Observable<any> {
    let params = new HttpParams();
    let data = {searchText: text, searchCity: city};

    for(const key in data) {
      params = params.append(key, data[key]);
    }
 
    return this.httpClient.get(`${this.baseUrl}`, { params });
  }
}
