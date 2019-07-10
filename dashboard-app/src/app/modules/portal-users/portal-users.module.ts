import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PortalUserDetailComponent } from './pages/portal-user-detail/portal-user-detail.component';
import { PortalUserListComponent } from './pages/portal-user-list/portal-user-list.component';
import {PotralUsersRoutingModule} from './potral-users-routing.module';
import {CoreModule} from '../core/core.module';
import { PortalUserFilterComponent } from './components/portal-user-filter/portal-user-filter.component';
import { PortalUserListItemComponent } from './components/portal-user-list-item/portal-user-list-item.component';
import { BackToListComponent } from './components/back-to-list/back-to-list.component';
import { DonorStatusComponent } from './components/donor-status/donor-status.component';
import { ExludeUserFromTargetingComponent } from './components/exlude-user-from-targeting/exlude-user-from-targeting.component';
import { EditPortalUserComponent } from './components/edit-portal-user/edit-portal-user.component';
import { UserDropdownComponent } from './components/user-dropdown/user-dropdown.component';

@NgModule({
    declarations: [PortalUserDetailComponent, PortalUserListComponent, PortalUserFilterComponent, PortalUserListItemComponent,
        BackToListComponent, DonorStatusComponent, ExludeUserFromTargetingComponent, EditPortalUserComponent, UserDropdownComponent],
    imports: [
        CommonModule,
        PotralUsersRoutingModule,
        CoreModule
    ],
    exports: [PortalUserDetailComponent, PortalUserListComponent, PortalUserFilterComponent, PortalUserListItemComponent,
        BackToListComponent, DonorStatusComponent, ExludeUserFromTargetingComponent, EditPortalUserComponent, UserDropdownComponent]
})
export class PortalUsersModule {
}
