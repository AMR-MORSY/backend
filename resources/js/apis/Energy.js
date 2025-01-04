import Csrf from "./Csrf";
import allInstances from "./Api";

export default {
    submitEnergySheet(sheet) {
     

        return allInstances.uploadApi.post("/energysheet/index", sheet);
    },
    getEnergySheetIndex() {
       
        return allInstances.Api.get("/energysheet/index");
    },
    getEnergyStatestics(data){
        return allInstances.Api.get(`/energysheet/statestics/${data.week}/${data.year}`);
    
    },
    getSitePowerAlarms(data){
        return allInstances.Api.post("energysheet/sitePowerAlarms",data);
    },
    getSiteHighTempAlarms(data){
        return allInstances.Api.post("energysheet/siteHighTempAlarms",data);
    },
    getSiteGenAlarms(data){
        return allInstances.Api.post("energysheet/siteGenAlarms",data);
    },
    downloadSitePowerAlarms(data)
    {
        return allInstances.downloadApi.post("energysheet/downloadSitePowerAlarms",data);
    },
    downloadSiteHighTempAlarms(data)
    {
        return allInstances.downloadApi.post("energysheet/downloadSiteHighTempAlarms",data);
    },
    downloadSiteGenAlarms(data)
    {
        return allInstances.downloadApi.post("energysheet/downloadSiteGenAlarms",data);
    },
    downloadZoneHTAlarms(data)
    {
        return allInstances.downloadApi.post("energysheet/downloadZoneHTAlarms",data);

    }
};
