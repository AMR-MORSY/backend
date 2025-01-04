<template>
  <div class="container mt-5">
    <div class="row mt-5">
      <div class="col-12 mt-5">
        <PickList
          v-model="pickListData"
          listStyle="height:342px"
          dataKey="cascade_code"
          @move-to-target="getPickListDataDeleted"
          @move-to-source="getPickListDataDeleted"
        >
          <template #sourceheader>
            <div class="header">
              <p>Cascades List</p>
              <div class="header-details">
                <p>Site Code</p>
                <p>site Name</p>
                <p>Cascades</p>
              </div>
            </div>
          </template>
          <template #targetheader>
            <div class="header">
              <p>Deleted List</p>
              <div class="header-details">
                <p>Site Code</p>
                <p>site Name</p>
                <p>Cascades</p>
              </div>
            </div>
          </template>
          <template #item="slotProps">
            <div class="product-item">
              <p class="product-category">{{ slotProps.item.cascade_code }}</p>
              <p class="product-category">{{ slotProps.item.cascade_name }}</p>

              <p class="mb-2">{{ slotProps.item.countCascades }}</p>
            </div>
          </template>
        </PickList>
      </div>
      <div class="col-4 mt-4">
        <div class="p-inputgroup">
          <InputText v-model="search" placeholder="Site Code......." />
          <Button icon="pi pi-search" @click.prevent="submitSearch" type="button" class="p-button-warning" />
        </div>
      </div>
      <div class="col-4 mt-2"></div>
      <div class="col-4 mt-4 mb-4">
        <Button
          label="Save"
          :disabled="isDisabled"
          icon="pi pi-check"
          iconPos="right"
          @click="updateCascades"
        />
      </div>
    </div>
  </div>
  <!-- <DynamicDialog :key="cascadesUpdate" /> -->
  <Toast />
</template>

<script>
import Sites from "../../../apis/Sites";
import UpdateSitesTable from "./UpdateSitesTable.vue";

export default {
  data() {
    return {
      siteName: null,
      siteCode: null,
      cascades: null,

      isDisabled: true,
      newCascades: [],

      initialCascadesCount: null,

      selectedSite: null,
      pickListData: null,
      search: null,
    };
  },
  components: {
    UpdateSitesTable,
  },
  name: "CascadesUpdate",
  props: ["site_code"],

  watch: {
    site_code() {
      this.getSiteDetails();
    },
  },
  emits:["displayNoneSpinner"],
  mounted() {
    this.getSiteDetails();
  },

  methods: {
    getPickListDataDeleted() {
      this.cascades = this.cascades.filter(
        (element) => this.newCascades.indexOf(element) == -1
      );
      let isFound = this.cascades.filter(
        (element) => this.pickListData[1].indexOf(element) !== -1
      );

      if (isFound.length > 0) {
        this.isDisabled = false;
        console.log(this.isDisabled);
      } else if (isFound.length == 0) {
        this.isDisabled = true;
        console.log(this.isDisabled);
      } else if (
        this.pickListData[1].length == 0 &&
        this.pickListData[0].length > this.initialCascadesCount
      ) {
        this.isDisabled = false;
        console.log(this.isDisabled);
      } else if (this.pickListData[0].length > this.initialCascadesCount) {
        this.isDisabled = false;
        console.log(this.isDisabled);
      }
    },
    updateCascades() {
      console.log(this.pickListData);
      let data = {
        cascades: [],
        siteCode: this.siteCode,
      };
      if (this.pickListData[0].length != 0) {
        data = {
          cascades: this.pickListData[0],
          siteCode: this.siteCode,
        };
      }

      console.log(data);
      Sites.updateCascades(data)
        .then((response) => {
          console.log(response);
          if ((response.status = 200)) {
            this.$toast.add({
              severity: "success",
              summary: "Successfully",
              detail: response.data.message,
              life: 3000,
            });
          }
        })
        .catch((error) => {
          console.log(error);
          if (error.response.status == 406) {
            error.response.data.errors.forEach((element) => {
              this.$toast.add({
                severity: "error",
                summary: "Oops",
                detail: element,
                life: 6000,
              });
            });
          } else if (error.response.status == 422) {
            if (error.response.data.errors.cascade_name) {
              error.response.data.errors.cascade_name.forEach((element) => {
                this.$toast.add({
                  severity: "error",
                  summary: "Oops",
                  detail: element,
                  life: 3000,
                });
              });
            }
            if (error.response.data.errors.cascade_code) {
              error.response.data.errors.cascade_name.forEach((element) => {
                this.$toast.add({
                  severity: "error",
                  summary: "Oops",
                  detail: element,
                  life: 3000,
                });
              });
            }
            if (error.response.data.errors.siteCode) {
              error.response.data.errors.cascade_name.forEach((element) => {
                this.$toast.add({
                  severity: "error",
                  summary: "Oops",
                  detail: element,
                  life: 3000,
                });
              });
            }
          }
        });
    },
    submitSearch() {
      this.$store.dispatch("displaySpinnerPage", false);
      Sites.getSiteDetails(this.search)
        .then((response) => {
          console.log(response);
          if (response.data.message == "No data Found") {
            this.$toast.add({
              severity: "info",
              summary: "Sorry!!!",
              detail: "No data Found",
              life: 3000,
            });
          } else {
            this.$dialog.open(UpdateSitesTable, {
              props: {
                header: "Sites",
                style: {
                  width: "75vw",
                },
                breakpoints: {
                  "960px": "75vw",
                  "640px": "90vw",
                },
              },
              data: {
                sites: [response.data.site],
              },
              onClose: (options) => {
                if (options.data) {
                  let newCascade = {
                    cascade_code: options.data.site_code,
                    cascade_name: options.data.site_name,
                    countCascades: response.data.cascades.length,
                  };
                  let isFound = false;
                  this.pickListData[0].forEach((element) => {
                    if (element.cascade_code == newCascade.cascade_code) {
                      isFound = true;
                    }
                  });
                  if (isFound) {
                    this.$toast.add({
                      severity: "error",
                      summary: "Opes!!!",
                      detail: "already in the list of cascades",
                      life: 3000,
                    });
                  } else {
                    this.pickListData[0].push(newCascade);
                    this.newCascades.push(newCascade);

                    this.isDisabled = false;
                  }
                }
              },
            });
          }
        })
        .catch((error) => {
          console.log(error);
          if (error.response.status == 422) {
            this.$toast.add({
              severity: "error",
              summary: "Opes!!!",
              detail: error.response.data.errors.site_code[0],
              life: 3000,
            });
          }
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },

    getSiteDetails() {
      Sites.getSiteDetails(this.site_code)
        .then((response) => {
          console.log(response);
          this.siteName = response.data.site.site_name;
          this.siteCode = response.data.site.site_code;

          this.cascades = response.data.cascades;
          this.initialCascadesCount = response.data.cascades.length;

          this.pickListData = [response.data.cascades, []];
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
:deep(.p-picklist) {
  .p-picklist-buttons button {
    background-color: var(--purple-500);
    border-color: var(--purple-500);
  }
}
.header {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  .header-details {
    width: 100%;
    align-items: center;
    display: flex;
    justify-content: space-around;
  }
}
.p-button {
  background-color: var(--purple-500);
  border-color: var(--purple-500);
}
.product-item {
  display: flex;
  align-items: center;
  justify-content: space-around;
  // padding: 0.5rem;
  width: 100%;
  p {
    text-align: start;
  }
}
.p-button-warning{
  color: white !important;
}

@media screen and (max-width: 576px) {
  .product-item {
    flex-wrap: wrap;

    .image-container {
      width: 100%;
      text-align: center;
    }

    img {
      margin: 0 0 1rem 0;
      width: 100px;
    }
  }
}
</style>