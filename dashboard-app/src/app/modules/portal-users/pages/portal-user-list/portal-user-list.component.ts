import {Component, OnInit} from '@angular/core';
import {PortalUserService} from "../../services/portal-user.service";
import {PortalUser} from "../../models/portal-user";
import {Router} from '@angular/router';
import {Routing} from '../../../../constants/config.constants';

@Component({
    selector: 'app-donor-list',
    templateUrl: './portal-user-list.component.html',
    styleUrls: ['./portal-user-list.component.scss']
})
export class PortalUserListComponent implements OnInit {

    alertOpen: boolean = false;
    alertType: string = '';
    alertMessage: string = '';
    loading: boolean = true;
    noUsers: boolean = false;
    users: PortalUser[];
    filterActive: boolean = false;

    constructor(private portalUserService: PortalUserService, private router: Router) {
    }

    ngOnInit() {
        this.getUsers();
    }

    getUsers() {
        this.portalUserService.getAll().subscribe((data: PortalUser[]) => {
            this.users = data;
            console.log(this.users)
            this.loading = false;
            if (data.length === 0) {
                this.noUsers = true;
            }
        });
    }

    showFilter() {
        this.filterActive = !this.filterActive;
    }

    redirectUserDetail(id: number) {
        console.log(Routing.PORTAL_USERS + '/' + id);
        this.router.navigateByUrl( Routing.PORTAL_USERS_FULL_PATH + '/' + id);
    }

}
