import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  form: FormGroup;

  hidePw = true;

  constructor(
    private fb: FormBuilder,
  ) {
    this.form = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      plainPassword: ['', Validators.required]
    });
  }

  onSubmit() {
    
  }
}
