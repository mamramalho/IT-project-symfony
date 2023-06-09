import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ManageVehiclesService } from '../services/manage-vehicles.service';
import { ManageReviewService } from '../services/manage-review.service';
import { Vehicle } from '../models/vehicle';
import { Review } from '../models/review';

import { GalleryItem, ImageItem } from 'ng-gallery';

@Component({
  selector: 'app-vehicle-details',
  templateUrl: './vehicle-details.component.html',
  styleUrls: ['./vehicle-details.component.css'],
  encapsulation: ViewEncapsulation.None,
})
export class VehicleDetailsComponent implements OnInit {

  vehicle: Vehicle;
  review: Review;

  specs = [];
  images = [];

  constructor(
    private route: ActivatedRoute,
    private vehicleService: ManageVehiclesService,
    private reviewService: ManageReviewService,
  ) { }

  ngOnInit() {
    this.route.paramMap.subscribe(params => {
      this.vehicleService.getVehicle(+params.get('id')).subscribe(
        (response) => {
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

          for(let src of this.vehicle.images) {
            this.images.push(
              new ImageItem({ src: src, thumb: src })
            );
          }
        }
      );
      this.reviewService.getReview(+params.get('id')).subscribe(
        (response) => {
          this.review = response as Review;
        }
      );
    });
  }
}
