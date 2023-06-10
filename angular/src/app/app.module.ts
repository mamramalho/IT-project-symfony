import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { MatTabsModule } from '@angular/material/tabs';
import { ReactiveFormsModule } from '@angular/forms';
import { MatInputModule } from '@angular/material/input';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { MatSelectModule } from '@angular/material/select';
import { FormsModule } from '@angular/forms';
import { CarouselModule } from '@coreui/angular';
import { MatTableModule } from '@angular/material/table';
import { GalleryModule, GALLERY_CONFIG } from  'ng-gallery';
import { MatDialogModule } from '@angular/material/dialog'; 

import { AppComponent } from './app.component';
import { HomeComponent } from './home/home.component';
import { AppRoutingModule } from './app-routing.module';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { LoginComponent } from './auth-dialog/login/login.component';
import { AuthService } from './services/auth.service';
import { RegistrationComponent } from './auth-dialog/registration/registration.component';
import { AnnouncementFormComponent } from './announcement-form/announcement-form.component';
import { AuthInterceptor } from './interceptors/auth.interceptor';
import { ManageVehiclesService } from './services/manage-vehicles.service';
import { VehicleDetailsComponent } from './vehicle-details/vehicle-details.component';
import { AuthDialogComponent } from './auth-dialog/auth-dialog.component';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    LoginComponent,
    RegistrationComponent,
    AnnouncementFormComponent,
    VehicleDetailsComponent,
    AuthDialogComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    MatToolbarModule,
    MatIconModule,
    MatButtonModule,
    MatTabsModule,
    ReactiveFormsModule,
    MatInputModule,
    HttpClientModule,
    MatProgressSpinnerModule,
    MatSelectModule,
    FormsModule,
    CarouselModule,
    MatTableModule,
    GalleryModule,
    MatDialogModule,
  ],
  providers: [
    AuthService,
    ManageVehiclesService,
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi: true
    },
    {
      provide: GALLERY_CONFIG,
      useValue: {
        dots: true,
        imageSize: 'cover'
      }
    }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
