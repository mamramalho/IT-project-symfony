import { Injectable } from '@angular/core';
import { HttpInterceptor, HttpRequest, HttpHandler, HttpEvent } from '@angular/common/http';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthInterceptor implements HttpInterceptor {

  constructor(private authService: AuthService) { }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    const token = this.authService.getToken();

    if (token) {
      const isTokenExpired = this.isTokenExpired(token);
      if (isTokenExpired) {
        this.authService.logout();
        // Perform any additional actions like redirecting to the login page
      } else {
        req = req.clone({
          setHeaders: {
            Authorization: `Bearer ${token}`
          }
        });
      }
    }

    return next.handle(req).pipe(
      tap(
        () => {},
        error => {
          // Handle authentication errors if needed
        }
      )
    );
  }

  private isTokenExpired(token: string): boolean {
    const expirationDate = this.getTokenExpirationDate(token);
    return expirationDate < new Date();
  }

  private getTokenExpirationDate(token: string): Date {
    const decodedToken = JSON.parse(atob(token.split('.')[1]));
    const expirationDate = new Date(decodedToken.exp * 1000);
    return expirationDate;
  }

}
