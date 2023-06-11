import { Component, Inject, ViewChild, ViewEncapsulation } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTabGroup } from '@angular/material/tabs';
import { Router } from '@angular/router';

@Component({
  selector: 'app-auth-dialog',
  templateUrl: './auth-dialog.component.html',
  styleUrls: ['./auth-dialog.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class AuthDialogComponent {
  @ViewChild('tabGroup') tabGroup!: MatTabGroup;

  isGoingToAnnounce: boolean;

  constructor(
    public dialogRef: MatDialogRef<AuthDialogComponent>,
    @Inject(MAT_DIALOG_DATA) private data: any,
    private router: Router,
    private _snackBar: MatSnackBar,
  ) {

    if (this.data != null) {
      this.isGoingToAnnounce = this.data.isGoingToAnnounce;
    }
  }

  selectLogin(): void {
    this.tabGroup.selectedIndex = 0;
  }

  closeDialog(): void {
    this.dialogRef.close();
    if (this.isGoingToAnnounce) {
      this.router.navigate(['/announce']);
    }

    const message = `User logged in`;
    this._snackBar.open(message, 'OK', {
      duration: 4000
    });
  }
}
