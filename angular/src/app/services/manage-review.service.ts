import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Review } from '../models/review';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ManageReviewService {
  
  private baseUrl = 'http://localhost:8888/api/review'

  constructor(private httpClient: HttpClient) { }

  addReview(review: Review): Observable<any> {
    const token = localStorage.getItem('access_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);

    return this.httpClient.post(this.baseUrl + '/new', review, { headers },);
  }

  getReviews(): Observable<any> {
    return this.httpClient.get(this.baseUrl + '/');
  }

  getReview(reviewId: number): Observable<any> {
    return this.httpClient.get(`${this.baseUrl}/${reviewId}`);
  }

  /* search(text: string|null, city: string|null): Observable<any> {
    let params = new HttpParams();
    let data = {searchText: text, searchCity: city};

    for(const key in data) {
      params = params.append(key, data[key]);
    }
 
    return this.httpClient.get(`${this.baseUrl}`, { params });
  } */
}
