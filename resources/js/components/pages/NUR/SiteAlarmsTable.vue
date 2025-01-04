<template>
  <div class="container">
    <div class="table-container" v-tooltip.right="'Get Tickets'">
      <div :class="{ display: displayNone, spinner: !displayNone }">
        <ProgressSpinner />
      </div>

      <!-- <DataTable
              :value="zoneNUR"
              responsiveLayout="scroll"
              class="p-datatable-sm"
              stripedRows
              v-model:selection="selectedSite"
              selectionMode="single"
              dataKey="siteCode"
              @row-select="onRowSelect"
            >
              <Column selectionMode="single"></Column>
              <slot name="columns"> </slot>

           
            </DataTable> -->
    </div>
  </div>
</template>

<script>
import Alarms from "../../../apis/Alarms";
export default {
  data() {
    return {
      displayNone: false,
    };
  },
  name: "SiteAlarmsTable",
  inject: ["dialogRef"],
  mounted() {
    this.getSiteAlarms();
  },
  methods: {
    getSiteAlarms() {
      let data = {
        siteCode: this.dialogRef.data,
      };

      Alarms.getSiteAlarms(data)
        .then((response) => {
          console.log("hello");
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.displayNone = true;
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