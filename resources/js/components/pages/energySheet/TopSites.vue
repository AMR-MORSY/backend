<template>
  <div>
    <Card>
      <template #title>
        <p style="font-size: 16px; pading: 0; text-align: center">
          <slot name="header"></slot>
        </p>
      </template>
      <template #content>
        <div class="table-container" v-tooltip.right="'Get Tickets'">
        
          <slot name="dataTable">
            <DataTable
              :value="zoneAlarms.alarms"
              responsiveLayout="scroll"
              class="p-datatable-sm"
              stripedRows
              :paginator="true"
              :rows="5"
              v-model:selection="selectedSite"
              selectionMode="single"
              dataKey="siteCode"
              @row-select="onRowSelect"
            >
              <Column selectionMode="single"></Column>
              <slot name="columns"> </slot>
            </DataTable>
          </slot>
        </div>
      </template>
    </Card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedSite: null,
  
    };
  },
  name: "TopSites",
  props: ["zoneAlarms"],
 
   methods: {
    onRowSelect() {

      this.$emit("siteCode",{siteCode:this.selectedSite.siteCode,alarmsName:this.zoneAlarms.alarmsName})

      // let data = {
      //   site_code: this.selectedSite.siteCode,
      // };
     
      // NUR.getSiteNUR(data)
      //   .then((response) => {
      //     console.log(response);
          
      //   })
      //   .catch((error) => {
      //     console.log(error);
      //   }).finally(()=>{
      //       this.displayNone=true;
      //   });
    },
  },
};
</script>

<style lang="scss" scoped>
// .display {
//   display: none;
// }
// .table-container {
//   position: relative;
//   .spinner {
//     position: absolute;
//     display: flex;
//     justify-content: center;
//     align-items: center;
//     top: 0;
//     left: 0;
//     bottom: 0;
//     right: 0;
//     z-index: 1;
//     background-color: rgba($color: #ffff, $alpha: 0.7);
//   }
// }
</style>