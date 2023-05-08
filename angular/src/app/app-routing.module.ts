import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { AuthComponent } from './auth/auth.component';
import { AuthGuard } from './auth.guard';
import { AnnouncementFormComponent } from './announcement-form/announcement-form.component';

const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'auth', component: AuthComponent },
  { path: 'announce', component: AnnouncementFormComponent, canActivate: [AuthGuard] }
];

@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule { }
