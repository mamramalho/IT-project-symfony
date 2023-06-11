import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ManageVehiclesService } from '../services/manage-vehicles.service';
import { ManageReviewService } from '../services/manage-review.service';
import { Vehicle } from '../models/vehicle';
import { Review } from '../models/review';

import { GalleryItem, ImageItem } from 'ng-gallery';
import { ManageUsersService } from '../services/manage-users.service';
import { User } from '../models/user';
import { MatSnackBar } from '@angular/material/snack-bar';

@Component({
  selector: 'app-vehicle-details',
  templateUrl: './vehicle-details.component.html',
  styleUrls: ['./vehicle-details.component.css'],
})
export class VehicleDetailsComponent implements OnInit {

  vehicle: Vehicle;
  announcer: User;
  reviews: Review[];

  specs = [];
  images = [];

  isReviewFormShowing = false;

  isPublishingReview = false;

  formReviewData = {
    content: ''
  }

  constructor(
    private route: ActivatedRoute,
    private vehicleService: ManageVehiclesService,
    private userService: ManageUsersService,
    private reviewService: ManageReviewService,
    private _snackBar: MatSnackBar,
  ) { }

  async ngOnInit() {
    this.route.paramMap.subscribe(async (params) => {
      const id = +params.get('id');
  
      try {
        const response = await this.vehicleService.getVehicle(id).toPromise();
        this.vehicle = response as Vehicle;
  
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

        this.userService.getUser(this.vehicle.userId).subscribe(
          async (response) => {
            this.announcer = response as User;
          }
        );

        this.vehicleService.getReviews(this.vehicle.id).subscribe(
          async (response) => {
            this.reviews = response as Review[];
            console.log(this.reviews);
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

  onSubmit() {
    this.isPublishingReview = true;
    const review = new Review(this.formReviewData.content);
    this.vehicleService.addReview(this.vehicle.id, review).subscribe(
      (response) => {
        this.isPublishingReview = false;
        this.isReviewFormShowing = false;
        console.log(response);
        const info = `Added a new review to ${this.vehicle.name}`;
        this._snackBar.open(info, 'OK', {
          duration: 4000,
        });
        this.vehicleService.getReviews(this.vehicle.id).subscribe(
          async (response) => {
            this.reviews = response as Review[];
            console.log(this.reviews);
          }
        );
      }
    );
  }
}
