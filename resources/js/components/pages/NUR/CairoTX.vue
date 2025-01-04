<template>
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <Chart
          type="doughnut"
          :data="ticketsType"
          :options="lightOptions"
          :plugins="plugins"
        />
      </div>
      <div class="col-md-4">
        <Chart
          type="doughnut"
          :data="accessStatus"
          :options="lightOptions"
          :plugins="plugins"
        />
      </div>
      <div class="col-md-4">
        <Chart
          type="doughnut"
          :data="TxType"
          :options="lightOptions"
          :plugins="plugins"
        />
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-between align-items-center">
    <h3>Sites</h3>
    <div
      style="
        display: flex;
        align-items: center;
        justify-content: center;
        width: 70%;
      "
    >
      <div
        class="w-25"
        style="display: flex; align-items: center; justify-content: center"
      >
        <img
          src="../../logos/week-icon.svg"
          @click="getCairoMWYearlyNUR"
          style="cursor: pointer"
          alt=""
          class="w-25"
          v-tooltip.right="'Weekly Analysis'"
        />
      </div>
    </div>
  </div>

  <div>
    <DataTable
      :value="sites"
      responsiveLayout="scroll"
      class="p-datatable-sm"
      stripedRows
      :rows="5"
      v-model:selection="selectedSite"
      selectionMode="single"
      dataKey="site_code"
      @row-select="onRowSelect"
    >
      <Column selectionMode="single"></Column>
      <Column field="site_name" header="Name"></Column>
      <Column field="site_code" header="Code"></Column>
      <Column field="NUR_2G_sum" header="NUR-2G" sortable></Column>
      <Column field="NUR_3G_sum" header="NUR-3G" sortable></Column>
      <Column field="NUR_4G_sum" header="NUR-4G" sortable></Column>
      <Column field="NUR_C" header="NUR-C" sortable></Column>
      <Column field="oz" header="oz" sortable></Column>
    </DataTable>

    <button class="btn btn-danger" @click="downloadTXTickets">Download</button>
  </div>
</template>

<script>
import NURTickets from "./NURTickets.vue";
import ChartDataLabels from "chartjs-plugin-datalabels";
import NUR from "../../../apis/NUR";
import exportFromJSON from "export-from-json";

import CairoTxYearlyAnalysis from "./CairoTxYearlyAnalysis.vue";
import allInstances from "../../../apis/Api";
export default {
  data() {
    return {
      sites: [],
      tickets: [],
      ticketsType: null,
      accessStatus: null,
      TxType: null,
      selectedSite: null,
      lightOptions: {
        plugins: {
          legend: {
            labels: {
              color: "red",
            },
          },
          datalabels: {
            anchor: "center",
            color: "red",
          },
        },
      },
      plugins: [ChartDataLabels],
    };
  },
  name: "CairoTx",
  components: {
    NURTickets,
    CairoTxYearlyAnalysis,
  },
  computed: {
    token() {
      return this.$store.getters.token;
    },
    isLogin() {
      return this.$store.getters.isLogin;
    },
  },
   beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (!vm.isLogin) {
        return vm.$router.push("/user/login");
      }
    });
  },
  inject: ["dialogRef"],
  mounted() {
    this.mountData();
  },
  methods: {
    mountData() {
      this.sites = this.dialogRef.data.sites;
      this.tickets = this.dialogRef.data.tickets;
      this.mountTicketsTypeChartData();
      this.mountAccessStatusChartData();
      this.mountTxTypeChartData();
    },
    mountTicketsTypeChartData() {
      let ticketsType = {
        voluntary: this.dialogRef.data.statestics.NUR_voluntary_c,
        involantary: this.dialogRef.data.statestics.NUR_involuntary_c,
      };
      this.ticketsType = {
        labels: Object.keys(ticketsType),
        datasets: [
          {
            data: Object.values(ticketsType),

            backgroundColor: [
              "#7F00FF",
              "#C3B1E1",
              "#E0B0FF",
              "#5D3FD3",
              "#CF9FFF",
              "#BF40BF",
              "#CCCCFF",
              "#BDB5D5",
              "#E6E6FA",
              "#AA98A9",
              "#953553",
              "#800080",
            ],
          },
        ],
      };
    },
    mountAccessStatusChartData() {
      let accessStatus = {
        Access: this.dialogRef.data.statestics.NUR_access_c,
        "Without Access": this.dialogRef.data.statestics.NUR_without_access_c,
      };
      this.accessStatus = {
        labels: Object.keys(accessStatus),
        datasets: [
          {
            data: Object.values(accessStatus),

            backgroundColor: [
              "#7F00FF",
              "#C3B1E1",
              "#E0B0FF",
              "#5D3FD3",
              "#CF9FFF",
              "#BF40BF",
              "#CCCCFF",
              "#BDB5D5",
              "#E6E6FA",
              "#AA98A9",
              "#953553",
              "#800080",
            ],
          },
        ],
      };
    },
    mountTxTypeChartData() {
      let TxType = {
        HDSL: this.dialogRef.data.statestics.NUR_HDSL_c,
        MW:
          this.dialogRef.data.statestics.NUR_combined -
          this.dialogRef.data.statestics.NUR_HDSL_c,
      };
      this.TxType = {
        labels: Object.keys(TxType),
        datasets: [
          {
            data: Object.values(TxType),

            backgroundColor: [
              "#7F00FF",
              "#C3B1E1",
              "#E0B0FF",
              "#5D3FD3",
              "#CF9FFF",
              "#BF40BF",
              "#CCCCFF",
              "#BDB5D5",
              "#E6E6FA",
              "#AA98A9",
              "#953553",
              "#800080",
            ],
          },
        ],
      };
    },
    onRowSelect() {
      let siteCode = this.selectedSite.site_code;
      console.log(this.selectedSite.site_code);
      let tickets_2G = this.tickets.filter((ticket) => {
        return (
          ticket.problem_site_code == siteCode && ticket.technology == "2G"
        );
      });
      let tickets_3G = this.tickets.filter((ticket) => {
        return (
          ticket.problem_site_code == siteCode && ticket.technology == "3G"
        );
      });
      let tickets_4G = this.tickets.filter((ticket) => {
        return (
          ticket.problem_site_code == siteCode && ticket.technology == "4G"
        );
      });

      this.$dialog.open(NURTickets, {
        props: {
          style: {
            width: "75vw",
          },
          breakpoints: {
            "960px": "75vw",
            "640px": "90vw",
          },
          modal: true,
        },

        data: {
          NUR_2G_tickets: tickets_2G,
          NUR_3G_tickets: tickets_3G,
          NUR_4G_tickets: tickets_4G,
        },
      });
    },
    downloadTXTickets() {
      const data = this.tickets;
      const fileName = "TxTickets";
      const exportType = exportFromJSON.types.xls;

      if (data) exportFromJSON({ data, fileName, exportType });
    },
    getCairoMWYearlyNUR() {
      // NUR.cairoTXYearlyAnalysis(this.tickets[0].year)
      allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;

      allInstances.Api.get(`/Nur/cairo/yearly/TXNUR/${this.tickets[0].year}`)
        .then((response) => {
          console.log(response);
          let labels = Object.keys(response.data.NUR_C_yearly.cairo);
          let cairo = response.data.NUR_C_yearly.cairo;
          let zones = response.data.NUR_C_yearly.zones;
          this.$dialog.open(CairoTxYearlyAnalysis, {
            props: {
              style: {
                width: "75vw",
              },
              breakpoints: {
                "960px": "75vw",
                "640px": "90vw",
              },
              modal: true,
            },

            data: {
              labels: labels,
              cairo: cairo,
              zones: zones,
            },
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>