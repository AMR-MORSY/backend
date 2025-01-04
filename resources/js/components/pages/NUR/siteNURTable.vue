<template>
  <div class="container">
    <div
      class="d-flex flex-column algin-items-center justify-content-center p-2"
    >
      <div class="d-flex algin-items-center justify-content-start p-1">
        <Button
          icon="pi pi-bell"
          class="p-button-rounded p-button-danger"
          @click="getSiteAlarms"
        />
      </div>

      <p style="color: red; font-weight: 600">Alarms</p>
    </div>
    <div v-if="count2G" class="mb-2">
      <DataTable
        :value="siteNUR2G"
        responsiveLayout="scroll"
        class="p-datatable-sm"
        stripedRows
      >
        <template #header>
          <div class="table-header">2G NUR</div>
        </template>
        <Column
          v-for="col in columns"
          :field="col.field"
          :header="col.header"
          :key="col.field"
        ></Column>
      </DataTable>
      <div class="mt-2">
        <button class="btn btn-secondary" @click="download2GNUR">
          Download 2G NUR
        </button>
      </div>
    </div>
    <div v-if="count3G" class="mb-2">
      <DataTable
        :value="siteNUR3G"
        responsiveLayout="scroll"
        class="p-datatable-sm"
        stripedRows
      >
        <template #header>
          <div class="table-header">3G NUR</div>
        </template>
        <Column
          v-for="col in columns"
          :field="col.field"
          :header="col.header"
          :key="col.field"
        ></Column>
      </DataTable>
      <div class="mt-2">
        <button class="btn btn-secondary" @click="download3GNUR">
          Download 3G NUR
        </button>
      </div>
    </div>
    <div v-if="count4G" class="mb-2">
      <DataTable
        :value="siteNUR4G"
        responsiveLayout="scroll"
        class="p-datatable-sm"
        stripedRows
      >
        <template #header>
          <div class="table-header">4G NUR</div>
        </template>
        <Column
          v-for="col in columns"
          :field="col.field"
          :header="col.header"
          :key="col.field"
        ></Column>
      </DataTable>
      <div class="mt-2">
        <button class="btn btn-secondary" @click="download4GNUR">
          Download 4G NUR
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import SiteAlarmsTable from "./SiteAlarmsTable.vue";
import NUR from "../../../apis/NUR";
import allInstances from "../../../apis/Api";
export default {
  data() {
    return {
      siteNUR3G: null,
      siteNUR2G: null,
      siteNUR4G: null,
      // siteName: null,
      // siteCode: null,
      count2G: false,
      count3G: false,
      count4G: false,
      columns: null,
    };
  },
  created() {
    this.columns = [
      { field: "week", header: "Week" },
      { field: "Dur_Hr", header: "Durations" },
      { field: "begin", header: "Start Time" },
      { field: "end", header: "End Time" },
      { field: "nur", header: "NUR" },
      { field: "sub_system", header: "Subsystem" },
      { field: "cells", header: "Cells" },
      { field: "impacted_sites", header: "impacted" },
    ];
  },
  components: {
    SiteAlarmsTable,
  },
  // emits: ["displayNoneSpinner"],
  inject: ["dialogRef"],
  // props:["siteNUR3G","siteNUR2G","siteNUR4G"],
  name: "siteNURTable",
  mounted() {
    this.checkNUR();
  },
   beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (!vm.isLogin) {
        return vm.$router.push("/user/login");
      }
    });
  },

  computed:{
      token() {
      return this.$store.getters.token;
    },
     isLogin()
    {
      return this.$store.getters.isLogin;
    }

  },
  methods: {
    getSiteAlarms() {
      // console.log(this.siteNUR3G);
      // if (this.count2G == true) {
      //   this.siteCode = this.siteNUR2G[0].problem_site_code;
      //   this.siteName = this.siteNUR2G[0].problem_site_name;
      // } else if (this.count3G == true) {
      //   this.siteCode = this.siteNUR3G[0].problem_site_code;
      //   this.siteName = this.siteNUR3G[0].problem_site_name;
      // } else if (this.count4G == true) {
      //   this.siteCode = this.siteNUR4G[0].problem_site_code;
      //   this.siteName = this.siteNUR4G[0].problem_site_name;
      // }
      this.$dialog.open(SiteAlarmsTable, {
        props: {
          header: this.siteName,
          style: {
            width: "75vw",
          },
          breakpoints: {
            "960px": "75vw",
            "640px": "90vw",
          },
          //   modal: true,
        },
        // templates: {
        //   footer: () => {
        //     return [
        //       h(Button, {
        //         label: "No",
        //         icon: "pi pi-times",
        //         onClick: () => dialogRef.close({ buttonType: "No" }),
        //         class: "p-button-text",
        //       }),
        //       h(Button, {
        //         label: "Yes",
        //         icon: "pi pi-check",
        //         onClick: () => dialogRef.close({ buttonType: "Yes" }),
        //         autofocus: true,
        //       }),
        //     ];
        //   },
        // },
        data: {
          site_code: this.dialogRef.data.site_code,
          site_name: this.dialogRef.data.site_name,
        },
      });
    },
    checkNUR() {
      this.siteNUR3G = this.dialogRef.data.NUR3G;
      if (this.siteNUR3G.length > 0) {
        this.count3G = true;
      }
      this.siteNUR2G = this.dialogRef.data.NUR2G;
      if (this.siteNUR2G.length > 0) {
        this.count2G = true;
      }
      this.siteNUR4G = this.dialogRef.data.NUR4G;
      if (this.siteNUR4G.length > 0) {
        this.count4G = true;
      }
    },
    download2GNUR() {
      let data = {
        site_code: this.dialogRef.data.NUR2G[0].problem_site_code,
      };
        allInstances.downloadApi.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;

      allInstances.downloadApi.post("/Nur/downloadNUR2G", data)
      // NUR.download2GNUR(data)
        .then((response) => {
          console.log(response);
          var fileUrl = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");
          fileLink.href = fileUrl;
          fileLink.setAttribute(
            "download",
            `${this.dialogRef.data.NUR2G[0].problem_site_name}NUR2G.xlsx`
          );
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {});
    },
    download3GNUR() {
      let data = {
        site_code: this.dialogRef.data.NUR3G[0].problem_site_code,
      };
     

           allInstances.downloadApi.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;

      allInstances.downloadApi.post("/Nur/downloadNUR3G", data)

      // NUR.download3GNUR(data)
        .then((response) => {
          console.log(response);
          var fileUrl = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");
          fileLink.href = fileUrl;
          fileLink.setAttribute(
            "download",
            `${this.dialogRef.data.NUR3G[0].problem_site_name}NUR3G.xlsx`
          );
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {});
    },
    download4GNUR() {
      let data = {
        site_code: this.dialogRef.data.NUR4G[0].problem_site_code,
      };
     allInstances.downloadApi.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;

      allInstances.downloadApi.post("/Nur/downloadNUR4G", data)
      // NUR.download4GNUR(data)
        .then((response) => {
          console.log(response);
          var fileUrl = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");
          fileLink.href = fileUrl;
          fileLink.setAttribute(
            "download",
            `${this.dialogRef.data.NUR4G[0].problem_site_name}NUR4G.xlsx`
          );
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {});
    },
  },
};
</script>

<style lang="scss" scoped>
</style>