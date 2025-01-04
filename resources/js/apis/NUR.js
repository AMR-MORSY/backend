import allInstances from "./Api";

export default {
    get2GNurIndex() {
        return allInstances.Api.get("/Nur/index");
    },
    submit2GNurSheet(sheet) {
        return allInstances.uploadApi.post("/Nur/2G", sheet);
    },
    get3GNurIndex() {
        return allInstances.Api.get("/Nur/index");
    },
    submit3GNurSheet(sheet) {
        return allInstances.uploadApi.post("/Nur/3G", sheet);
    },
    get4GNurIndex() {
        return allInstances.Api.get("/Nur/index");
    },
    submit4GNurSheet(sheet) {
        return allInstances.uploadApi.post("/Nur/4G", sheet);
    },
    getNur(data)
    {
        return allInstances.Api.get(`/Nur/show/${data.week}/${data.year}`);

    },
    getSiteNUR(siteCode)
    {
        return allInstances.Api.post("/Nur/siteNUR", siteCode);

    },
    download2GNUR(data)
    {
        return allInstances.downloadApi.post("/Nur/downloadNUR2G", data)
    },
    download3GNUR(data)
    {
        return allInstances.downloadApi.post("/Nur/downloadNUR3G", data)
    },
    download4GNUR(data)
    {
        return allInstances.downloadApi.post("/Nur/downloadNUR4G", data)
    },
    getVipSitesWeeklyNUR(zone,week,year)
    {
        return allInstances.Api.get(`/Nur/vip/week/${zone}/${week}/${year}`);
    },
    getNodalSitesWeeklyNUR(zone,week,year)
    {
        return allInstances.Api.get(`/Nur/nodal/week/${zone}/${week}/${year}`);
    },
    getCairoMWWeeklyNUR(week,year)
    {
        return allInstances.Api.get(`/Nur/cairo/weekly/MWNUR/${week}/${year}`);
    },
    getCairoGenWeeklyNUR(week,year)
    {
        return allInstances.Api.get(`/Nur/cairo/weekly/GenNUR/${week}/${year}`);
    },
    getCairoPowerWeeklyNUR(week,year)
    {
        return allInstances.Api.get(`/Nur/cairo/weekly/PowerNUR/${week}/${year}`);
    },
    cairoNUR_CYearlyAnalysis(year)
    {
        return allInstances.Api.get(`/Nur/cairo/yearly/NUR_C/${year}`);
    },
    cairoGenYearlyAnalysis(year)
    {
        return allInstances.Api.get(`/Nur/cairo/yearly/GenNUR/${year}`);
    },
    cairoTXYearlyAnalysis(year)
    {
        return allInstances.Api.get(`/Nur/cairo/yearly/TXNUR/${year}`);
    },


    
};
