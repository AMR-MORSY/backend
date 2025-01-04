import allInstances from "./Api";

export default{
    getSiteAlarms(siteCode){
        return allInstances.Api.post("/energysheet/alarms",siteCode);
    }
}