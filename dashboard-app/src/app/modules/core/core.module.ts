import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {RouterModule} from "@angular/router";
import {InlineSVGModule} from "ng-inline-svg";
import {NgxSelectModule} from "ngx-select-ex";
import { ColorPickerModule } from 'ngx-color-picker';
import {NgbModule} from "@ng-bootstrap/ng-bootstrap";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {AngularEditorModule} from "@kolkov/angular-editor";
import {NgCircleProgressModule} from "ng-circle-progress";
import {BreadcrumbsComponent, GoogleFontSettingsComponent} from "./parts/molecules";
import {
    AlertComponent,
    ButtonComponent, CheckboxComponent,
    DatepickerComponent, DragAndDropComponent,
    DropdownComponent, InputGroupComponent, InputNumberComponent, LoadingComponent,
    ProgressComponent, RadioButtonComponent, RadioButtonGroupComponent,
    SwitcherComponent
}  from "./parts/atoms";
import {ActionsComponent} from "../../components";
import {PreviewMonetizationComponent, WidgetSettingsComponent} from "../campaigns/components";
import {SafePipe} from "./pipes/safe.pipe";
import {SlovakNumberFormatter} from "./pipes/SlovakNumberFormatter";


@NgModule({
    declarations: [
        BreadcrumbsComponent,
        DatepickerComponent,
        ProgressComponent,
        ButtonComponent,
        DropdownComponent,
        SwitcherComponent,
        DragAndDropComponent,
        CheckboxComponent,
        RadioButtonComponent,
        AlertComponent,
        LoadingComponent,
        InputNumberComponent,
        ActionsComponent,
        RadioButtonGroupComponent,
        InputGroupComponent,
        GoogleFontSettingsComponent,
        WidgetSettingsComponent,
        PreviewMonetizationComponent,

        SafePipe,
        SlovakNumberFormatter
    ],
    imports: [
        CommonModule,
        RouterModule,
        InlineSVGModule.forRoot(),
        ColorPickerModule,
        NgxSelectModule,
        NgbModule,
        AngularEditorModule,
        FormsModule,
        NgCircleProgressModule.forRoot({
            space: -5
        }),
    ],
    exports: [

        FormsModule,
        ReactiveFormsModule,
        //components
        BreadcrumbsComponent,
        DatepickerComponent,
        ProgressComponent,
        ButtonComponent,
        DropdownComponent,
        SwitcherComponent,
        DragAndDropComponent,
        CheckboxComponent,
        RadioButtonComponent,
        AlertComponent,
        LoadingComponent,
        InputNumberComponent,
        ActionsComponent,
        RadioButtonGroupComponent,
        InputGroupComponent,
        GoogleFontSettingsComponent,
        WidgetSettingsComponent,
        PreviewMonetizationComponent,

        //pipes
        SafePipe,
        SlovakNumberFormatter,
        //modules
        CommonModule,
        RouterModule,
        NgbModule,
        FormsModule,
        //custom modules
        ColorPickerModule,
        NgxSelectModule,
        AngularEditorModule,

    ]
})
export class CoreModule {
}
