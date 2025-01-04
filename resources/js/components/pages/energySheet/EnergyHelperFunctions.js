import Energy from "../../../apis/Energy";
import store from "../../../vuex/store";

export default {
    getSitePowerAlarms(siteCode) {
        let data = {
            site_code: siteCode,
        };

        store.dispatch("displaySpinnerPage", false);
        Energy.getSitePowerAlarms(data)
            .then((response) => {
                store.dispatch("siteAlarms",{"alarmName":"power","alarmData":response.data.alarms});
            })
            .catch((error) => {
                // console.log(error)
            })
            .finally(() => {
                store.dispatch("displaySpinnerPage", true);
            });
    },
    getSiteHighTempAlarms(siteCode) {
        let data = {
            site_code: siteCode,
        };

        store.dispatch("displaySpinnerPage", false);
        Energy.getSiteHighTempAlarms(data)
            .then((response) => {
                store.dispatch("siteAlarms", {"alarmName":"highTemp","alarmData":response.data.alarms});
            })
            .catch((error) => {
                // console.log(error)
            })
            .finally(() => {
                store.dispatch("displaySpinnerPage", true);
            });
    },
    getSiteGenAlarms(siteCode) {
        let data = {
            site_code: siteCode,
        };

        store.dispatch("displaySpinnerPage", false);
        Energy.getSiteGenAlarms(data)
            .then((response) => {
                store.dispatch("siteAlarms", {"alarmName":"gen","alarmData":response.data.alarms});
            })
            .catch((error) => {
                // console.log(error)
            })
            .finally(() => {
                store.dispatch("displaySpinnerPage", true);
            });
    },
    downloadZoneHTSites(zone ,period,period_No) {
        console.log({zone,period});
        let data={
            period:period,
            zone:zone,
            period_No:period_No
        };
        Energy.downloadZoneHTAlarms(data).then((response)=>{
            var fileUrl = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement("a");
            fileLink.href = fileUrl;
            fileLink.setAttribute("download", `${zone}HTAlarmsweek${period}.xlsx`);
            document.body.appendChild(fileLink);
            fileLink.click();
          
        })
    },
};
