import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-announcement-form',
  templateUrl: './announcement-form.component.html',
  styleUrls: ['./announcement-form.component.css']
})
export class AnnouncementFormComponent {
  form: FormGroup;

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

  selectedImageUrls: string[] = [];
  
  constructor(
    private fb: FormBuilder,
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
      price: ['', Validators.required, Validators.pattern(/^\d+$/)],
      description: [''],
      color: [''],
      fuel: ['', Validators.required],
      plate: ['', Validators.required],
      kms: ['', Validators.required],
    });

  }

  onImagesSelected(event: any) {
    const files: File[] = event.target.files;
    const imageUrls: string[] = [];

    for (const file of files) {
      const reader = new FileReader();
      reader.onload = () => {
        imageUrls.push(reader.result as string);
        this.selectedImageUrls = [...this.selectedImageUrls, ...imageUrls];
      };
      reader.readAsDataURL(file);
    }
    console.log(this.selectedImageUrls);
  }

  removeImage(index: number) {
    this.selectedImageUrls.splice(index, 1);
  }

  onSubmit() {

  }
}
