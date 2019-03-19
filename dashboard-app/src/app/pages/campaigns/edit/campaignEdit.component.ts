import {ChangeDetectorRef, Component, EventEmitter, OnDestroy, OnInit, Output, ViewChild} from '@angular/core';
import {AngularEditorConfig} from '@kolkov/angular-editor';
import {Campaign} from "app/_models/campaign";
import {ActivatedRoute, Router} from "@angular/router";
import {Routing} from "app/constants/config.constants";
import {CampaignService} from "app/_services/campaign.service";
import {ComponentCommunicationService} from "../../../_services/component-communication.service";
import {devices} from "app/_models/enums";
import {PreviewService} from "../../../_services/preview.service";
import {WidgetService} from "../../../_services/widget.service";
import {Widget} from "../../../_models/widget";
import {Subscription} from "rxjs";
import {environment} from 'environments/environment';
import {backgroundTypes} from "../../../_models/enums";

@Component({
    selector: 'app-campaign-edit',
    templateUrl: './campaignEdit.component.html',
    styleUrls: ['../../../../sass/classes.scss', './campaignEdit.component.scss']
})

export class CampaignEditComponent implements OnInit {
    @Output() closeEdit: EventEmitter<any> = new EventEmitter();
    public activeContent: string = 'campaignSettings';
    campaign: Campaign = new Campaign();
    public widgets: Widget[] = [];

    //property with purpose to detect changes before submitting edited campaign
    id: string;
    public routing = Routing;
    public newCampaign: boolean;
    public loading = true;
    public error = false;
    submitted = false;
    public errorMessage;
    public saving: boolean = false;
    public previewOpen;
    deviceType = devices.desktop.name;
    creatingHTMLs = false;
    public createdCampaign;
    public campaignId: any;
    @ViewChild('previewGenerate') previewGenerate;
    public environment = environment;

    public subscription: Subscription;
    backgroundTypes = backgroundTypes;
    editorConfig: AngularEditorConfig = {
        editable: true,
        spellcheck: true,
        height: '25rem',
        minHeight: '5rem',
        placeholder: 'Enter text here...',
        translate: 'no',
        uploadUrl: 'v1/images', // if needed
        customClasses: [ // optional
            {
                name: 'quote',
                class: 'quote',
            },
            {
                name: 'redText',
                class: 'redText'
            },
            {
                name: 'titleText',
                class: 'titleText',
                tag: 'h1',
            },
        ]
    };


    constructor(private router: Router, private route: ActivatedRoute, private campaignService: CampaignService, private componentComService: ComponentCommunicationService,
                private previewService: PreviewService, private widgetService: WidgetService, private ref: ChangeDetectorRef) {
        this.campaign = new Campaign();

    }

    ngOnInit() {
        this.newCampaign = this.route.snapshot.data['new'];
        if (!this.newCampaign) {
            //get id from param map or when param map doesn't contains param id, try to get id from its parent's param map
            this.id = this.route.snapshot.paramMap.get("id") ? this.route.snapshot.paramMap.get("id") : this.route.parent.snapshot.paramMap.get("id");
            this.campaignService.getCampaignById(this.id).subscribe((result: any) => {
                this.campaign = result.data;
                this.campaignService.writeDatesAsJson(this.campaign);
                this.loading = false;
                this.widgetService.getListByCampaignId(this.id).subscribe(result => {
                    this.widgets = result.data;
                    this.loading = false;
                });
            }, (error) => {
                console.error(error);
            });
        } else {
            this.loading = false;
        }

    }


    closeEditWindow() {
        let targetUrl = this.router.url.split("/(" + this.routing.RIGHT_OUTLET)[0];
        this.router.navigateByUrl(targetUrl);
    }

    handleSubmit() {
        this.submitted = true;
        if (this.validInput()) {
            this.saving = true;
            if (this.newCampaign) {
                this.campaignService.createCampaign(this.campaign).subscribe(
                    campaignResult => {
                        this.campaignId = campaignResult.campaign_id;
                        this.createdCampaign = campaignResult;
                        this.creatingHTMLs = true;
                        this.ref.detectChanges();
                        this.previewGenerate.generateHTMLFromWidgets().subscribe(
                            htmlReadyToSend => {
                                this.campaignService.updateWidgetsHTML(this.campaignId, htmlReadyToSend).subscribe( result=> {
                                    this.componentComService.setAlertMessage(`Campaign ${this.campaign.name} created`);
                                    let targetUrl = this.router.url.split("/(" + this.routing.RIGHT_OUTLET)[0];
                                    this.router.navigateByUrl(targetUrl);
                                    this.creatingHTMLs = false;
                                 });
                            }
                        )
                    }
                )
            } else {
                this.campaignService.updateCampaign(this.campaign).subscribe(
                    campaignResult => {
                        this.campaignId = campaignResult.campaign_id;
                        this.createdCampaign = campaignResult;
                        this.creatingHTMLs = true;
                        this.ref.detectChanges();
                        this.previewGenerate.generateHTMLFromWidgets().subscribe(
                            htmlReadyToSend => {
                                this.campaignService.updateWidgetsHTML(this.campaignId, htmlReadyToSend).subscribe( result => {
                                    this.componentComService.setAlertMessage(`Campaign ${this.campaign.name} updated`);
                                    let targetUrl = this.router.url.split("/(" + this.routing.RIGHT_OUTLET)[0];
                                    this.router.navigateByUrl(targetUrl);
                                    this.creatingHTMLs = false;
                                });
                            })
                    }
                )
            }
        }
    }

    validInput() {
        if (!this.campaign.name) {
            this.errorMessage = "Campaign name is required";
            this.error = true;
            return false;
        }
        if (!this.campaign.description) {
            this.errorMessage = "Campaign description is required";
            this.error = true;
            return false;
        }
        if (!this.campaign.headline_text){
            this.errorMessage = "Headline text is required";
            this.error = true;
            return false;
        }
        if (!this.campaign.date_to) {
            this.errorMessage = "End date of campaign is required";
            this.error = true;
            return false;
        }
        if (!this.campaign.widget_settings.general.background.image.url && (
            this.campaign.widget_settings.general.background.type == this.backgroundTypes.image.value ||
            this.campaign.widget_settings.general.background.type == this.backgroundTypes.imageOverlay.value)) {
            this.errorMessage = "Please upload image or change background type";
            this.error = true;
            return false;
        }
        return true;
    }


}

