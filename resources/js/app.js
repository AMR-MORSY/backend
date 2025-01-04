import './bootstrap';
import { createApp } from 'vue';

import app from "./components/app.vue";
import router from "./router";
import modal from "./components/helpers/modal.vue";////declared globally
import spinnerButton from "./components/helpers/spinnerButton.vue";
import helperTable from "./components/helpers/helperTable.vue";
import store from "./vuex/store";
import navbar from "./components/navbar.vue";
import VueChartkick from 'vue-chartkick';
import 'chartkick/chart.js';
import PrimeVue from 'primevue/config';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import  "primevue/resources/themes/saga-blue/theme.css";      //theme
import  "primevue/resources/primevue.min.css";                 //core css
import  "primeicons/primeicons.css";
import Card from 'primevue/card';
import Password from 'primevue/password';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Chart from 'primevue/chart';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';
import Checkbox from 'primevue/checkbox';
import Tooltip from 'primevue/tooltip';
import ProgressSpinner from 'primevue/progressspinner';
import DialogService from 'primevue/dialogservice';
import DynamicDialog from 'primevue/dynamicdialog';
import FileUpload from 'primevue/fileupload';
import ProgressBar from 'primevue/progressbar';
import Badge from 'primevue/badge';
import SpinnerPage from "./components/helpers/SpinnerPage.vue";
import Details from "./components/pages/sites/Details.vue";
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import PickList from 'primevue/picklist';
import Fieldset from 'primevue/fieldset';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Divider from 'primevue/divider';
import ConfirmationService from 'primevue/confirmationservice';
import ConfirmDialog from 'primevue/confirmdialog';
import SpeedDial from 'primevue/speeddial';














createApp(app)
.use(store)
.use(router)
.use(VueChartkick)
.use(PrimeVue)
.use(ToastService)
.use(DialogService)
.use(ConfirmationService)

.directive('tooltip', Tooltip)

.component('Datepicker', Datepicker)
.component('ConfirmDialog', ConfirmDialog)

.component("Toast",Toast)
.component("SpinnerPage",SpinnerPage)
.component("Fieldset",Fieldset)
.component("DynamicDialog",DynamicDialog)
.component("modal",modal)
.component("Dropdown",Dropdown)
.component("Divider",Divider)
.component("Calendar",Calendar)
.component("Textarea",Textarea)
.component("Details",Details)
.component("Dialog",Dialog)
.component("Badge",Badge)
.component("TabView",TabView)
.component("TabPanel",TabPanel)
.component("ProgressBar",ProgressBar)
.component("FileUpload",FileUpload)
.component("Card",Card)
.component("PickList",PickList)
.component("ProgressSpinner",ProgressSpinner)
.component("Checkbox",Checkbox)
.component("Password",Password)
.component("InputText",InputText)
.component("DataTable",DataTable)
.component("Column",Column)
.component("Chart",Chart)
.component("SpeedDial",SpeedDial)
.component("Button",Button)
.component("navbar",navbar)
.component("spinnerButton",spinnerButton)
.component("helperTable",helperTable)
// .component("downloadExcel", JsonExcel)
.mount("#app");

