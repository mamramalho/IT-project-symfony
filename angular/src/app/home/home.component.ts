import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ManageVehiclesService } from '../services/manage-vehicles.service';
import { Vehicle } from '../models/vehicle';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
  encapsulation: ViewEncapsulation.None,
})
export class HomeComponent implements OnInit {
  searchText: string = '';
  city: string = '';

  cities: string[] = [
    'dolnośląskie',
    'kujawsko-pomorskie',
    'lubelskie',
    'lubuskie',
    'łódzkie',
    'małopolskie',
    'mazowieckie',
    'opolskie',
    'podkarpackie',
    'podlaskie',
    'pomorskie',
    'śląskie',
    'świętokrzyskie',
    'warmińsko-mazurskie',
    'wielkopolskie',
    'zachodniopomorskie',
  ];

  vehicles: Vehicle[] = [];

  isFetching = true;

  constructor(
    private authService: AuthService,
    private vehicleService: ManageVehiclesService
  ) {}

  ngOnInit(): void {
    this.vehicleService.getVehicles().subscribe(
      (response) => {
        this.vehicles = response as Vehicle[];
        console.log(this.vehicles);
        this.isFetching = false;
      }
    );
  }

  isLoggedIn(): boolean {
    return this.authService.isLoggedIn();
  }

  logout(): void {
    this.authService.logout();
  }
  
  onSelected(city: string): void {
    this.city = city;
    this.searchVehicle();
  }

  searchVehicle() {
    this.isFetching = true;
    if (this.city == '') {
      this.city = 'Poland';
    }
    this.vehicleService.search(this.searchText, this.city).subscribe(
      (response) => {
        this.vehicles = response as Vehicle[];
        this.isFetching = false;
      }
    );
  }

}
