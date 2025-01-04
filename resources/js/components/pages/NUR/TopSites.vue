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
          <div :class="{ display: displayNone, spinner: !displayNone }">
            <ProgressSpinner />
          </div>
          <slot name="dataTable">
            <DataTable
              :value="zoneNUR"
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
import NUR from "../../../apis/NUR";
import allInstances from "../../../apis/Api";
export default {
  data() {
    return {
      selectedSite: null,
      displayNone: true,
    };
  },
  name: "TopSites",
  props: ["zoneNUR"],
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
    onRowSelect() {
      this.displayNone = false;

      let data = {
        site_code: this.selectedSite.siteCode,
      };
        allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
     allInstances.Api.post("/Nur/siteNUR", data)
      // NUR.getSiteNUR(data)
        .then((response) => {
          console.log(response);
          this.$emit("siteNUR",response.data)
        })
        .catch((error) => {
          console.log(error);
        }).finally(()=>{
            this.displayNone=true;
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