import {Component, OnInit, OnDestroy} from '@angular/core';
import {Subscription} from 'rxjs';
import {first} from 'rxjs/operators';

import {User} from '../_models';
import {UserService, AuthenticationService} from '../_services';
import {Campaign} from '../_models/campaign';

@Component({templateUrl: 'home.component.html'})
export class HomeComponent implements OnInit, OnDestroy {
  public currentUser: User;
  public currentUserSubscription: Subscription;
  users: User[] = [];
  public testCampaign: Campaign;

  constructor(
    public authenticationService: AuthenticationService,
    private userService: UserService
  ) {
    this.currentUserSubscription = this.authenticationService.currentUser.subscribe(user => {
      this.currentUser = user;
    });
  }

  ngOnInit() {
    this.loadAllUsers();

    this.testCampaign = new Campaign();
  }

  ngOnDestroy() {
    // unsubscribe to ensure no memory leaks
    this.currentUserSubscription.unsubscribe();
  }

  deleteUser(id: number) {
    this.userService.delete(id).pipe(first()).subscribe(() => {
      this.loadAllUsers();
    });
  }

  private loadAllUsers() {
    this.userService.getAll().subscribe((users: User[]) => {
      this.users = users;
    });
  }
}
