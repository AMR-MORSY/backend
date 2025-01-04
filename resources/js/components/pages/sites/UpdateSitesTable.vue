<template>
  <div>
    <DataTable
      :value="sites"
      responsiveLayout="scroll"
      class="p-datatable-sm"
      :paginator="true"
      :rows="5"
      stripedRows
      v-model:selection="selectedSite"
      selectionMode="single"
      dataKey="site_code"
      @row-select="onRowSelect"
    >
      <Column selectionMode="single"></Column>
      <Column field="site_code" header="Site Code"></Column>
      <Column field="site_name" header="Site Name"></Column>
      <Column field="oz" header="Zone"></Column>
    </DataTable>
  </div>
</template>

<script>
export default {
  data() {
    return {
      displaySpinnerPage: true,
      sites: null,
      selectedSite:null,
    };
  },
  name: "UpdateSitesTable",
  inject: ["dialogRef"],

  methods: {
    onRowSelect() {
        // console.log(this.selectedSite);
      
          this.dialogRef.close(this.selectedSite);
       
    },
  },
  mounted() {
    this.sites = this.dialogRef.data.sites;
  },
};
</script>

<style lang="scss" scoped>
.table-container {
  position: relative;
}
</style>