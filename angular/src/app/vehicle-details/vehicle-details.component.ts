import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { using } from 'rxjs';
import { ManageVehiclesService } from '../services/manage-vehicles.service';
import { ManageReviewService } from '../services/manage-review.service';
import { Vehicle } from '../models/vehicle';
import { Review } from '../models/review';

@Component({
  selector: 'app-vehicle-details',
  templateUrl: './vehicle-details.component.html',
  styleUrls: ['./vehicle-details.component.css']
})
export class VehicleDetailsComponent implements OnInit {

  vehicle: Vehicle;
  review: Review;

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
