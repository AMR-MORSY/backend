import { createRouter, createWebHistory } from "vue-router";
import Sheet from "../components/pages/energySheet/Sheet.vue";
import notFount from "../components/notFound.vue";
import newSitesInsert from "../components/pages/sites/newSitesInsert.vue";
import newSiteInsert from "../components/pages/sites/newSiteInsert.vue";
import login from "../components/pages/User/login.vue";
import register from "../components/pages/User/register.vue";
import home from "../components/home.vue";
import resetPassword from "../components/pages/User/resetPassword.vue";
import unauthorized from "../components/unauthorized.vue";
import nurIndex from "../components/pages/NUR/nurIndex.vue";
import NUR2G from "../components/pages/NUR/NUR2G.vue";
import NUR3G from "../components/pages/NUR/NUR3G.vue";
import NUR4G from "../components/pages/NUR/NUR4G.vue";
import NURStatestics from "../components/pages/NUR/NURStatestics.vue";
import cascades from "../components/pages/sites/cascades.vue";
import nodals from "../components/pages/sites/nodals.vue";
import modifications from "../components/pages/Modifications/modifications.vue";
import modificationsIndex from "../components/pages/Modifications/modificationsIndex.vue";
import energyIndex from "../components/pages/energySheet/energyIndex.vue";
import Details from "../components/pages/sites/Details.vue";
import CascadesUpdate from "../components/pages/sites/CascadesUpdate.vue";
import SiteModifications from "../components/pages/Modifications/SiteModifications.vue";
import NewModification from "../components/pages/Modifications/NewModification.vue";
import UpdateModification from "../components/pages/Modifications/UpdateModification.vue";
import dashboard from "../components/pages/dashboard.vue";
import NUR from "../components/pages/NUR/NUR.vue";
import energy from "../components/pages/energySheet/energy.vue";
import sites from "../components/pages/sites/sites.vue";
import users from "../components/pages/User/users.vue";
import siteUpdate from "../components/pages/sites/siteUpdate.vue";
import energyStatestics from "../components/pages/energySheet/energyStatestics.vue";
 
const routes = [
    { path: "/energy/sheet", component: Sheet },
    { path: "/energy", component: energy },
    { path: "/energy/index", component: energyIndex },
    { path: "/energy/statestics/:week/:year", component: energyStatestics,props:true },
    { path: "/dashboard", component: dashboard },
    {path:"/modifications/index/:columnName/:columnValue",component:modificationsIndex,props:true},
    {path:"/modifications/new/:site_code/:site_name",component:NewModification,props:true},
    {path:"/modifications/update/:id",component:UpdateModification,props:true},
    { path: "/sites", component: sites },
    {path:"/sites/details/:site_code",component:Details,props:true},
    { path: "/sites/cascades", component: cascades },
    { path: "/sites/storeSites", component: newSitesInsert },
    { path: "/sites/storeSite", component: newSiteInsert },
    { path: "/sites/update/:siteCode", component: siteUpdate,props:true },
    { path: "/sites/nodals", component: nodals },
    {path:"/sites/cascades/update/:site_code",component:CascadesUpdate,props:true},
    { path: "/modifications", component: modifications },
    { path: "/modifications/sitemodifications/:site_code/:site_name", component: SiteModifications,props:true },
    { path: "/nur", component: NUR },
    { path: "/nur/index", component: nurIndex },
    { path: "/nur/statestics/:week/:year", component: NURStatestics,props:true },
    { path: "/nur/2G", component: NUR2G },
    { path: "/nur/3G", component: NUR3G },
    { path: "/nur/4G", component: NUR4G },
    { path: "/", redirect: "/home" },
    { path: "/home", component: home },
    { path: "/:pathMatch(.*)*", component: notFount },
    { path: "/users", component: users },
    { path: "/user/login", component: login, name: "login" },
    { path: "/user/register", component: register },
    { path: "/user/resetpassword", component: resetPassword },
    { path: "/unauthorized", component: unauthorized },
];
const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
