<template>
  <div
    class="table-container"
    v-tooltip.right="'Get Tickets'"
    v-if="alarmsData"
  >
    <DataTable
      :value="alarmsData"
      responsiveLayout="scroll"
      class="p-datatable-sm"
      stripedRows
      :paginator="true"
      :rows="5"
      selectionMode="single"
    >
    
      <Column field="site_name" header="Site Name"></Column>
      <Column field="alarm_name" header="Alarm Name"></Column>
      <Column field="start_date" header="Start Date" sortable></Column>
        <Column field="start_time" header="Start Time"></Column>
      <Column field="end_date" header="End Date"></Column>
        <Column field="end_time" header="End Time"></Column>
      <Column field="duration" header="Duration min" sortable></Column>
      <Column field="week" header="Week" sortable></Column>
      <Column field="year" header="Year" sortable></Column>
    </DataTable>
  </div>
  <template v-if="alarmsData">
    <Button
      type="button"
      class="p-button-help"
      @click="
        downloadSiteAlarms(
          alarmsData[0].site_code,
          alarmName,
          alarmsData[0].site_name
        )
      "
    >
      <span class="material-symbols-sharp">download</span>
      <span class="font-bold">Download</span>
    </Button>
  </template>
</template>

<script>
import Energy from "../../../apis/Energy";

export default {
  data() {
    return {
    
      alarmsData: null,
      alarmName: null,
    };
  },
 
  mounted() {
    this.mountTableData();
  },

  name: "SiteAlarmsTable",
  methods: {
    downloadSiteAlarms(siteCode, alarmName, siteName) {
      let data = {
        siteCode: siteCode,
      };

      if (alarmName == "power") {
        Energy.downloadSitePowerAlarms(data)
          .then((response) => {
            console.log(response);
            var fileUrl = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement("a");
            fileLink.href = fileUrl;
            fileLink.setAttribute("download", `${siteName}PowerAlarms.xlsx`);
            document.body.appendChild(fileLink);
            fileLink.click();
          })
          .catch((error) => {});
      } else if (alarmName == "highTemp") {
        Energy.downloadSiteHighTempAlarms(data)
          .then((response) => {
            console.log(response);
            var fileUrl = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement("a");
            fileLink.href = fileUrl;
            fileLink.setAttribute("download", `${siteName}HighTempAlarms.xlsx`);
            document.body.appendChild(fileLink);
            fileLink.click();
          })
          .catch((error) => {});
      } else if (alarmName == "gen") {
        Energy.downloadSiteGenAlarms(data)
          .then((response) => {
            console.log(response);
            var fileUrl = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement("a");
            fileLink.href = fileUrl;
            fileLink.setAttribute("download", `${siteName}GenAlarms.xlsx`);
            document.body.appendChild(fileLink);
            fileLink.click();
          })
          .catch((error) => {});
      }
    },

    mountTableData() {
    
     this.alarmsData = this.$store.state.siteAlarms.alarmData;
    this.alarmName = this.$store.state.siteAlarms.alarmName;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>