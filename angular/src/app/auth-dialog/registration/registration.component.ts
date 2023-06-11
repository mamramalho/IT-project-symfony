import { Component, EventEmitter, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { User } from 'src/app/models/user';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-registration',
  templateUrl: './registration.component.html',
  styleUrls: ['./registration.component.css']
})
export class RegistrationComponent {
  @Output() doneRegistration = new EventEmitter<void>();

  form: FormGroup;

  requestInProgress = false;

  hidePw = true;

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private _snackBar: MatSnackBar,
  ) {
    this.form = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      plainPassword: ['', Validators.required]
    });
  }

  onSubmit() {
    const email = this.form.controls['email'].value;
    const plainPassword = this.form.controls['plainPassword'].value;

    const user: User = new User(email, plainPassword);

    this.requestInProgress = true;
    this.authService.register(user).subscribe((response) => {
        console.log(response);
        this.requestInProgress = false;
        this.doneRegistration.emit();

        const message = 'New user registered';
        this._snackBar.open(message, 'OK', {
          duration: 4000
        });
      }
    );
  }
}
