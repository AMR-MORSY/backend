<template>
  <div class="d-flex justify-content-center align-items-center">
    <p class="w-100 text-center"><span style="color:gray; font-weight=500;">NUR: </span><span style="color:red; font-weight=500;">{{NUR}}</span></p>
  </div>
  <div class="container">
    <div class="row">
        <div class="col"></div>
        <div class="col-4">
             <Chart
          type="doughnut"
          :data="ticketsType"
          :options="lightOptions"
          :plugins="plugins"
        />
        </div>
        <div class="col"></div>
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

    <button class="btn btn-danger" @click="downloadPowerTickets">Download</button>
  </div>
</template>

<script>
import NURTickets from "./NURTickets.vue";
import ChartDataLabels from "chartjs-plugin-datalabels";
import exportFromJSON from "export-from-json";
export default {
  data() {
    return {
      sites: [],
      tickets: [],
      ticketsType: null,
     NUR:null,
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
  name: "CairoMainPower",
  inject: ["dialogRef"],
  components: {
    NURTickets,
    
  },
  mounted() {
    this.mountData();
  },
  methods: {
     mountData() {
      this.sites = this.dialogRef.data.sites;
      this.tickets = this.dialogRef.data.tickets;
      this.NUR=this.dialogRef.data.statestics.NUR_combined;
       let ticketsType = {
        Access: this.dialogRef.data.statestics.NUR_access_c,
        No_Access: this.dialogRef.data.statestics.NUR_without_access_c,
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
     onRowSelect()
     {

     },
    downloadPowerTickets()
    { const data = this.tickets;
      const fileName = "TxTickets";
      const exportType = exportFromJSON.types.xls;

      if (data) exportFromJSON({ data, fileName, exportType });

    }
  },
};
</script>

<style lang="scss" scoped>
</style>