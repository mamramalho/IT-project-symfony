import { Component, OnInit } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ManageVehiclesService } from '../services/manage-vehicles.service';
import { Vehicle } from '../models/vehicle';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
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

  constructor(
    private authService: AuthService,
    private vehicleService: ManageVehiclesService
  ) {}

  ngOnInit(): void {
    this.vehicleService.getVehicles().subscribe(
      (response) => {
        this.vehicles = response as Vehicle[];
        console.log(this.vehicles);
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
    console.log(city);
  }


}
