import { Component, ViewChild } from '@angular/core';
import { MatTabGroup } from '@angular/material/tabs';

@Component({
  selector: 'app-auth',
  templateUrl: './auth.component.html',
  styleUrls: ['./auth.component.css']
})
export class AuthComponent {
  @ViewChild('tabGroup') tabGroup!: MatTabGroup;

  selectLogin(): void {
    this.tabGroup.selectedIndex = 0;
  }
}
