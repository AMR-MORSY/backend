<template>
  <div class="container mt-5 mb-5">
    <div class="row mt-5">
      <!-- <div class="col-12 col-lg-1"></div> -->
      <div class="col-12 col-lg-12">
        <div class="card mt-5">
          <template v-if="modifications.length > 0">
            <DataTable
              :value="modifications"
              :paginator="true"
              :rows="5"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              :rowsPerPageOptions="[5, 10, 15]"
              responsiveLayout="scroll"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
              class="p-datatable-sm"
              stripedRows
              @row-select="onRowSelect"
              v-model:selection="selectedModification"
            >
              <Column selectionMode="single"></Column>
              <Column field="site_code" header="Code"></Column>
              <Column field="site_name" header="Name"></Column>
              <Column field="subcontractor" header="Subcontractor"></Column>
              <Column field="requester" header="Requester"></Column>
              <Column field="action" header="Action"></Column>
              <Column field="status" header="Status"></Column>
              <Column field="project" header="Project"></Column>
              <Column field="request_date" header="Request Date"></Column>
              <Column field="finish_date" header="Finish Date"></Column>
              <Column field="cost" header="Cost"></Column>
              <template #footer>
                <div class="d-flex justify-content-end align-items-center">
                  Total Cost {{ totalCost }} LE.
                </div>
              </template>
              <template #paginatorstart>
                <Button
                  type="button"
                  icon="pi pi-refresh"
                  class="p-button-text"
                />
              </template>
              <template #paginatorend>
                <Button
                  type="button"
                  icon="pi pi-cloud"
                  class="p-button-text"
                />
              </template>
            </DataTable>
            <div class="buttons">
              <div class="my-3">
                <button
                  class="btn btn-secondary"
                  style="margin-left: 3rem"
                  @click="downloadModfications"
                >
                  Download
                </button>
              </div>
              <div class="my-3">
                <Button
                  label="Update"
                  @click="gotToUpdateModification"
                  class="p-button-raised p-button-warning"
                  :disabled="!isRowSelected"
                />
              </div>
              <div class="my-3">
                <Button
                  label="Delete"
                  @click="deleteModification"
                  class="p-button-raised p-button-danger"
                  :disabled="!isRowSelected"
                />
              </div>
            </div>
            <!-- <download-excel
              class="btn btn-default"
              :data="downloadModifications"
              worksheet="My Worksheet"
              name="Modifications.xls"
            >
              Download Excel
            </download-excel> -->
          </template>

          <template v-else>
            <div class="no-modification">
              <p>No Modifications Available</p>
            </div>
          </template>
        </div>
      </div>
      <!-- <div class="col-12 col-lg-1"></div> -->
    </div>
  </div>
</template>

<script>
import Modifications from "../../../apis/Modifications";
import allInstances from "../../../apis/Api";
export default {
  data() {
    return {
      modifications: [],
      isRowSelected: false,
      selectedModification: null,
    };
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (!vm.isLogin) {
        return vm.$router.push("/user/login");
      }
    });
  },
  computed: {
    totalCost() {
      if (!this.modifications) {
        return 0;
      } else {
        return this.modifications.reduce(function (sum, current) {
          return sum + Number(current.cost);
        }, 0);
      }
    },
    downloadModifications() {
      return this.modifications;
    },
    isAdmin() {
      return this.$store.getters.isAdmin;
    },
    token() {
      return this.$store.getters.token;
    },
     isLogin()
    {
      return this.$store.getters.isLogin;
    }
  },
  props: ["columnName", "columnValue"],
  created() {
    this.getModificationsIndex();
  },

  name: "modificationsIndex",
  methods: {
    getModificationsIndex() {
      this.$store.dispatch("displaySpinnerPage", false);
      let data = {
        columnName: this.columnName,
        columnValue: this.columnValue,
      };

      // Modifications.getModificationIndex(data)
      allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
      allInstances.Api.get(
        `/modifications/index/${data.columnName}/${data.columnValue}`
      )
        .then((response) => {
          this.modifications = response.data.modifications;
        })
        .catch((error) => {
          if (error.response.status == 422) {
            let errors = error.response.data.errors;
            if (errors.columnName) {
              errors.columnName.forEach((element) => {
                this.$toast.add({
                  severity: "error",
                  summary: "Error Message",
                  detail: element,
                  life: 3000,
                });
              });
            } else if (errors.columnValue) {
              errors.columnValue.forEach((element) => {
                this.$toast.add({
                  severity: "error",
                  summary: "Error Message",
                  detail: element,
                  life: 3000,
                });
              });
            }
          }
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },

    downloadModfications() {
      let data = {
        column_name: this.columnName,
        column_value: this.columnValue,
      };

      allInstances.downloadApi.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;

      allInstances.downloadApi
        .post("/modifications/download", data)

        // Modifications.downloadModifications(data)
        .then((response) => {
          console.log(response);
          var fileUrl = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");
          fileLink.href = fileUrl;
          fileLink.setAttribute(
            "download",
            `${this.columnValue}Modifications.xlsx`
          );
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch((error) => {});
    },
    onRowSelect() {
      if (this.isAdmin[0].name == "super-admin") {
        this.isRowSelected = true;
      }
    },
    gotToUpdateModification() {
      this.$router.push(
        `/modifications/update/${this.selectedModification.id}`
      );
    },

    deleteModification() {
      this.$confirm.require({
        message: "Are you sure you want to proceed?",
        header: "Confirmation",
        icon: "pi pi-exclamation-triangle",
        accept: () => {
          this.$confirm.close();
          this.isRowSelected = false;
          this.$store.dispatch("displaySpinnerPage", false);
          let data = {
            id: this.selectedModification.id,
          };

          // Modifications.deleteModification(data)
          allInstances.downloadApi.defaults.headers[
            "Authorization"
          ] = `Bearer ${this.token}`;

          allInstances.Api.post("/modifications/delete", data)
            .then((response) => {
              this.getModificationsIndex();
            })
            .catch((error) => {});
        },
        reject: () => {
          this.$confirm.close();
          this.isRowSelected = false;
          //callback to execute when user rejects the action
        },
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.no-modification {
  padding: 4rem;
  display: flex;
  align-items: center;
  justify-content: center;
  p {
    color: red;
  }
}
.buttons {
  display: flex;
  align-items: center;
  justify-content: space-around;
}
</style>