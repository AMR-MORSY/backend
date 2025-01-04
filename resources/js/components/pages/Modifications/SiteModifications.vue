<template>
  <section id="site-modification" v-if="isModificationsFound">
    <div class="container mt-5">
      <Fieldset class="mt-5">
        <template #legend>{{ site_code }}-{{ site_name }} </template>
        <div class="row mt-5">
          <div class="col-12">
            <DataTable
              :value="modifications"
              responsiveLayout="scroll"
              class="p-datatable-sm"
              stripedRows
              selectionMode="single"
              v-model:selection="selectedModification"
              @row-select="onRowSelect"
            >
              <template #header> </template>
              <Column selectionMode="single"></Column>

              <Column
                v-for="col in columns"
                :field="col.field"
                :header="col.header"
                :key="col.field"
              ></Column>
            </DataTable>
          </div>
          <div class="col-6 col-md-4 mt-3">
            <Button
              label="Update"
              @click="gotToUpdateModification"
              class="p-button-raised p-button-warning"
              :disabled="!isRowSelected"
            />
          </div>
          <div class="col-6 col-md-4 mt-3">
            <Button
              label="New Modification"
              @click="insertNewModification"
              class="p-button-raised p-button-secondary"
            />
          </div>
          <div class="col-6 col-md-4 mt-3">
            <Button
              label="Delete"
              @click="deleteModification"
              class="p-button-raised p-button-danger"
              :disabled="!isRowSelected"
            />
          </div>
        </div>
      </Fieldset>
    </div>
  </section>

  <section v-else>
    <transition name="fade-bounce" appear>
      <div class="container">
        <div class="errors card">
          <p>No Modifications</p>
          <div class="buttons">
            <Button
              label="New Modification"
              @click="insertNewModification"
              class="p-button-raised p-button-secondary"
            />

            <Button
              label="Back"
              @click="goBack"
              class="p-button-raised p-button-danger"
            />
          </div>
        </div>
      </div>
    </transition>
  </section>
  <!-- <ConfirmDialog :key="modifications"></ConfirmDialog> -->
</template>

<script>
import Modifications from "../../../apis/Modifications";
import allInstances from "../../../apis/Api";
export default {
  data() {
    return {
      modifications: null,
      selectedModification: null,
      isRowSelected: false,
      isModificationsFound: false,
    };
  },
  name: "SiteModifications",
  props: ["site_code", "site_name"],
  emits: ["displayNoneSpinner"],
  watch: {
    site_code() {
      this.getSiteModifications();
    },
  },
  beforeMount() {
    this.getSiteModifications();
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (!vm.isLogin) {
        return vm.$router.push("/user/login");
      }
    });
  },
  computed: {
    token() {
      return this.$store.getters.token;
    },
    isLogin() {
      return this.$store.getters.isLogin;
    },
  },
  mounted() {
    this.columns = [
      { field: "subcontractor", header: "Subcontractor" },
      { field: "action", header: "Action" },
      { field: "requester", header: "Requester" },
      { field: "request_date", header: "Request Date" },
      { field: "project", header: "Project" },
      { field: "finish_date", header: "Finish Date" },
      { field: "status", header: "Status" },
      { field: "cost", header: "Cost" },
      { field: "materials", header: "Materials" },
    ];
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    getSiteModifications() {
      this.$store.dispatch("displaySpinnerPage", false);
      allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
      allInstances.Api.get(`/modifications/siteModifications/${this.site_code}`)
        // Modifications.getSiteModifications(this.site_code)
        .then((response) => {
          console.log(response);
          this.modifications = response.data.modifications;
          if (this.modifications.length > 0) {
            this.isModificationsFound = true;
          }
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },
    onRowSelect() {
      this.isRowSelected = true;
    },
    gotToUpdateModification() {
      this.$router.push(
        `/modifications/update/${this.selectedModification.id}`
      );
    },
    insertNewModification() {
      this.$router.push(
        `/modifications/new/${this.site_code}/${this.site_name}`
      );
    },
    deleteModification() {
      this.$confirm.require({
        message: "Are you sure you want to proceed?",
        header: "Confirmation",
        icon: "pi pi-exclamation-triangle",
        accept: () => {
          this.$confirm.close();
          this.$store.dispatch("displaySpinnerPage", false);
          let data = {
            id: this.selectedModification.id,
          };
          allInstances.Api.defaults.headers[
            "Authorization"
          ] = `Bearer ${this.token}`;
          allInstances.Api.post("/modifications/delete", data)

            // Modifications.deleteModification(data)
            .then((response) => {
              this.getSiteModifications();
            })
            .catch((error) => {});
        },
        reject: () => {
          this.$confirm.close();
          //callback to execute when user rejects the action
        },
      });
    },
  },
};
</script>

<style lang="scss" scoped>
#site-modification {
  margin-top: 5em;
}
::v-deep(.p-fieldset) {
  position: relative;

  .p-fieldset-legend {
    width: 25%;
    color: white;
    text-align: center;
    position: absolute;
    top: 30px;
    left: 50px;
    z-index: 2;
    background-color: rgba($color: gray, $alpha: 0.9);
  }
  // .p-fieldset-content {
  //   background-color: green;
  // }
  .p-datatable {
    width: 100%;
    margin-top: 50px;
    z-index: 1;
    .p-datatable-header {
      height: 50px;
    }
  }
}

.errors {
  margin-top: 4em;
  padding: 3rem;
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-content: center;
  p {
    color: red;
    text-align: center;
  }
  .buttons {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
}

.fade-bounce-enter-active {
  animation: woble 1s ease;
}
@keyframes woble {
  0% {
    opacity: 0;
    transform: translateY(-300px);
  }
  50% {
    opacity: 1;
    transform: translateY(0px);
  }
  60% {
    transform: translateY(16px);
  }
  70% {
    transform: translateY(-16px);
  }
  80% {
    transform: translateY(8px);
  }
  90% {
    transform: translateY(-8px);
  }
  100% {
    transform: translateY(0px);
  }
}
@media screen and (max-width: 576px) {
}
</style>