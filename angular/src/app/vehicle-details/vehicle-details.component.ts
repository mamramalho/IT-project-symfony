import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ManageVehiclesService } from '../services/manage-vehicles.service';
import { ManageReviewService } from '../services/manage-review.service';
import { Vehicle } from '../models/vehicle';
import { Review } from '../models/review';

import { GalleryItem, ImageItem } from 'ng-gallery';
import { ManageUsersService } from '../services/manage-users.service';
import { User } from '../models/user';

@Component({
  selector: 'app-vehicle-details',
  templateUrl: './vehicle-details.component.html',
  styleUrls: ['./vehicle-details.component.css'],
})
export class VehicleDetailsComponent implements OnInit {

  vehicle: Vehicle;
  announcer: User;

  specs = [];
  images = [];

  constructor(
    private route: ActivatedRoute,
    private vehicleService: ManageVehiclesService,
    private userService: ManageUsersService,
  ) { }

  async ngOnInit() {
    this.route.paramMap.subscribe(async (params) => {
      const id = +params.get('id');
  
      try {
        const response = await this.vehicleService.getVehicle(id).toPromise();
        this.vehicle = response as Vehicle;

        console.log(this.vehicle);
  
        this.specs = [
          { spec: 'Brand', value: this.vehicle.company },
          { spec: 'Type', value: this.vehicle.type },
          { spec: 'Model', value: this.vehicle.model },
          { spec: 'Year', value: this.vehicle.year },
          { spec: 'Register Year', value: this.vehicle.registerYear },
          { spec: 'Color', value: this.vehicle.color },
          { spec: 'Fuel', value: this.vehicle.fuel },
          { spec: 'Plate', value: this.vehicle.plate },
          { spec: 'Kilometers', value: this.vehicle.kms },
          { spec: 'Region', value: this.vehicle.city },
        ];
  
        for (const src of this.vehicle.images) {
          this.images.push(new ImageItem({ src: src, thumb: src }));
        }

        console.log(this.vehicle.userId);

        this.userService.getUser(this.vehicle.userId).subscribe(
          (response) => {
            this.announcer = response as User;
          }
        );

        

      } catch (error) {
        console.error(error);
      }
    });
  }

  openChat() {
    //TODO
  }
}
