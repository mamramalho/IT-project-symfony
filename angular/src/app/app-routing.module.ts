import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { AuthGuard } from './auth.guard';
import { AnnouncementFormComponent } from './announcement-form/announcement-form.component';
import { VehicleDetailsComponent } from './vehicle-details/vehicle-details.component';

const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'announce', component: AnnouncementFormComponent, canActivate: [AuthGuard] },
  { path: 'vehicle_details/:id', component: VehicleDetailsComponent }
];

@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule { }
