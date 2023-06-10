import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';

import { Vehicle } from 'src/app/models/vehicle';
import { ManageVehiclesService } from '../services/manage-vehicles.service';
import { Router } from '@angular/router';
import { MatSnackBar } from '@angular/material/snack-bar';

@Component({
  selector: 'app-announcement-form',
  templateUrl: './announcement-form.component.html',
  styleUrls: ['./announcement-form.component.css']
})
export class AnnouncementFormComponent {
  form: FormGroup;

  areas: string[] = [
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

  years: number[] = [];
  colors = [
    { name: 'White', code: '#FFFFFF' },
    { name: 'Black', code: '#000000' },
    { name: 'Gray', code: '#808080' },
    { name: 'Silver', code: '#C0C0C0' },
    { name: 'Blue', code: '#4169E1' },
    { name: 'Red', code: '#FF0000' },
    { name: 'Brown', code: '#A52A2A' },
    { name: 'Green', code: '#006400' },
    { name: 'Orange', code: '#FFA500' },
    { name: 'Beige', code: '#F5F5DC' },
    { name: 'Purple', code: '#800080' },
    { name: 'Gold', code: '#FFD700' },
    { name: 'Yellow', code: '#FFFF00' }
  ];

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

  selectedImageUrls: string[] = [];

  requestInProgress = false;
  

  constructor(
    private fb: FormBuilder,
    private vehicleService: ManageVehiclesService,
    private router: Router,
    private _snackBar: MatSnackBar,
  ) {
    const currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= 1886; year--) {
      this.years.push(year);
    }

    this.form = this.fb.group({
      title: ['', Validators.required],
      type: ['', Validators.required],
      brand: ['', Validators.required],
      model: ['', Validators.required],
      year: ['', Validators.required],
      registerYear: ['', Validators.required],
      price: ['', [Validators.required, Validators.pattern(/^\d+$/)]],
      description: ['', Validators.required],
      color: ['', Validators.required],
      fuel: ['', Validators.required],
      plate: ['', Validators.required],
      kms: ['', [Validators.required, Validators.pattern(/^\d+$/)]],
      city: ['', Validators.required],
    });

  }

  onImagesSelected(event: any) {
    const files: File[] = event.target.files;
    const imageUrls: string[] = [];

    for (const file of files) {
      const reader = new FileReader();
      reader.onload = () => {
        imageUrls.push(reader.result as string);
        this.selectedImageUrls.push(imageUrls.at(imageUrls.length-1) as string);
      };
      reader.readAsDataURL(file);
    }
    console.log(this.selectedImageUrls);
  }

  removeImage(index: number) {
    this.selectedImageUrls.splice(index, 1);
  }

  onSubmit() {
    const name = this.form.controls['title'].value;
    const company = this.form.controls['brand'].value;
    const type = this.form.controls['type'].value;
    const model = this.form.controls['model'].value;
    const year = this.form.controls['year'].value;
    const registerYear = this.form.controls['registerYear'].value;
    const price = this.form.controls['price'].value;
    const description = this.form.controls['description'].value;
    const color = this.form.controls['color'].value.name;
    const fuel = this.form.controls['fuel'].value;
    const plate = this.form.controls['plate'].value;
    const kms = this.form.controls['kms'].value;
    const city = this.form.controls['city'].value;

    const vehicle = new Vehicle(
      name,
      company,
      type,
      model,
      year,
      registerYear,
      price,
      description,
      color,
      fuel,
      plate,
      kms,
      this.selectedImageUrls,
      city
    );

    this.requestInProgress = true;

    this.vehicleService.addVehicle(vehicle).subscribe(
      (response) => {
        this.requestInProgress = false;
        this.router.navigate(['']);
        this._snackBar.open('New vehicle added', 'OK', {
          duration: 4000
        });
      },
      (error) => {
        console.log(error);
        this.requestInProgress = false;
        this._snackBar.open('Image is too large, choose a smaller one', 'OK', {
          duration: 4000
        });
      }
    );
  }
}
