import { Component } from '@angular/core';
import { AuthService } from './services/auth.service';
import { MatDialog } from '@angular/material/dialog';
import { AuthDialogComponent } from './auth-dialog/auth-dialog.component';
import { Router } from '@angular/router';
import { ManageUsersService } from './services/manage-users.service';
import jwt_decode from 'jwt-decode';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'it-project-angular';

  currentUser: string;

  constructor(
    private authService: AuthService,
    public dialog: MatDialog,
    private router: Router,
    private userService: ManageUsersService,
  ) {}

  isLoggedIn(): boolean {
    return this.authService.isLoggedIn();
  }

  logout(): void {
    this.authService.logout();
  }

  accountOrAuth() {
    const access_token = localStorage.getItem('access_token')
    if (access_token) {
      let decodedToken = jwt_decode(access_token) as any;
      this.router.navigate([`/users/${decodedToken.username}`]);
    }
    else {
      this.dialog.open(AuthDialogComponent, {
        width: '380px',
      });
    }
  }

  getCurrentUser() {
    const username = localStorage.getItem('currentUser');

    if(username != null) {
      return username;
    }

    return 'Log in or register';
  }
  
}
