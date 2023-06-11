import { Component, OnInit } from '@angular/core';
import { ManageUsersService } from '../services/manage-users.service';
import { User } from '../models/user';
import { ActivatedRoute } from '@angular/router';
import { ManageVehiclesService } from '../services/manage-vehicles.service';
import { Vehicle } from '../models/vehicle';

@Component({
  selector: 'app-account-details',
  templateUrl: './account-details.component.html',
  styleUrls: ['./account-details.component.css']
})
export class AccountDetailsComponent implements OnInit {

  user: User;
  vehicles: Vehicle[];

  constructor(
    private route: ActivatedRoute,
    private userService: ManageUsersService,
    private vehicleService: ManageVehiclesService,
  ) {}

  ngOnInit(): void {
    this.route.paramMap.subscribe(
      async (params) => {
        const email = params.get('email');

        this.userService.getUserByEmail(email).subscribe(
          async (response) => {
            this.user = response as User;
            console.log(response);

            this.vehicleService.getMyVehicles().subscribe(
              async (response) => {
                this.vehicles = response as Vehicle[];
                console.log(response);
              }
            );
          }
        );
      }
    );

    
  }
}
