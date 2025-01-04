<template>
  <div>
    <div class="table-container" v-tooltip.right="'Get Tickets'">
      <div :class="{ display: displayNone, spinner: !displayNone }">
        <ProgressSpinner />
      </div>

      <DataTable
        :value="vipOrNodalSites"
        responsiveLayout="scroll"
        class="p-datatable-sm"
        stripedRows
        :paginator="true"
        :rows="5"
        v-model:selection="selectedSite"
        selectionMode="single"
        dataKey="site_code"
        @row-select="onRowSelect"
      >
        <Column selectionMode="single"></Column>

        <Column field="site_name" header="Site Name"></Column>
        <Column field="site_code" header="site_code"></Column>
        <Column field="NUR_2G_count_tickets" header="2G tickets"></Column>
        <Column field="NUR_2G_sum_nur" header="2G NUR"></Column>
        <Column field="NUR_3G_count_tickets" header="3G tickets"></Column>
        <Column field="NUR_3G_sum_nur" header="3G NUR"></Column>
        <Column field="NUR_4G_count_tickets" header="4G tickets"></Column>
        <Column field="NUR_4G_sum_nur" header="4G NUR"></Column>
      </DataTable>
    </div>
  </div>
</template>

<script>
import NURTickets from "./NURTickets.vue";

export default {
  data() {
    return {
      selectedSite: null,
      displayNone: true,
      vipOrNodalSites: [],
    };
  },
  name: "VipsOrNodals",
  components: {
    NURTickets,
  },
  inject: ["dialogRef"],
  mounted() {
    this.mountTable();
  },

  methods: {
    mountTable() {
      this.$store.dispatch("displaySpinnerPage", true);
      this.vipOrNodalSites = this.dialogRef.data.sites;
    },

    onRowSelect() {
      console.log(this.selectedSite);
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
          NUR_2G_tickets: this.selectedSite.NUR_2G_tickets,
          NUR_3G_tickets: this.selectedSite.NUR_3G_tickets,
          NUR_4G_tickets: this.selectedSite.NUR_4G_tickets,
        },
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.display {
  display: none;
}
.table-container {
  position: relative;
  .spinner {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    z-index: 1;
    background-color: rgba($color: #ffff, $alpha: 0.7);
  }
}
</style>