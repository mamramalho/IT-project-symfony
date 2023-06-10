import { Component, EventEmitter, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { User } from 'src/app/models/user';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  @Output() doneLogin = new EventEmitter<void>();

  form: FormGroup;

  hidePw = true;
  requestInProgress = false;

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router
  ) {
    this.form = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      plainPassword: ['', Validators.required]
    });
  }

  onSubmit() {
    this.requestInProgress = true;

    const email = this.form.controls['email'].value;
    const plainPassword = this.form.controls['plainPassword'].value;

    const credentials = {
      username: email,
      password: plainPassword,
    }

    this.authService.login(credentials).subscribe((response) => {
      console.log(response);
      this.requestInProgress = false;
      this.doneLogin.emit();
      //this.router.navigate(['']);
    });
  }
}
