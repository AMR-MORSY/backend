<template>
  <div class="container mt-5">
    <form
      id="energysheet"
      @submit.prevent="submitCascadesSheet"
      enctype="multipart/form-data"
    >
      <div class="row index">
        <div class="col-12">
          <div v-if="serverError">
            {{ serverError }}
          </div>
        </div>

        <div class="col-12">
          <div class="form-group">
            <label for="sites">Cascades:</label>
            <input
              type="file"
              name="energy_sheet"
              class="form-control"
              @change="cascadesFile"
              id="sites"
              @focus="clearErrors"
            />
            <div v-if="cascadeErrors">
              <ul>
                <li
                  v-for="error in cascadeErrors"
                  style="color: red"
                  :key="error"
                >
                  {{ error }}
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-12 mt-2">
          <spinner-button
            type="submit"
            :show-spinner="showSpinner"
            class="btn btn-primary"
          >
            <span> Submit</span>
          </spinner-button>
        </div>
      </div>
    </form>

    <div class="helper-table-container">
      <helper-table v-if="cascadesErrors">
        <template #header>
          <th scope="col">Row</th>
          <th scope="col">Attribute</th>
          <th scope="col">Errors</th>
          <th scope="col">Values</th>
        </template>
        <template #body>
          <tr
            style="background-color: white; color: red"
            v-for="error in cascadesErrors"
            :key="error"
          >
            <td class="text-left align-middle">{{ error.row }}</td>
            <td class="text-left align-middle">{{ error.attribute }}</td>
            <td class="text-left align-middle">
              <ul v-for="rowError in error.errors" :key="rowError">
                <li>{{ rowError }}</li>
              </ul>
            </td>
            <td class="text-left align-middle">
              <ul>
                <li>Site Code:{{ error.values["Site Code"] }}</li>
                <li>Site Name:{{ error.values["Site Name"] }}</li>
              </ul>
            </td>
          </tr>
        </template>
      </helper-table>
    </div>
  </div>

  <modal :visible="showModal">
    <template #body>
      <p class="text-center">{{ successMessage }}</p>
    </template>
    <template #footer>
      <button class="btn btn-danger" @click="closeModal">close</button>
    </template>
  </modal>

  <!-- <button class="btn btn-info" @click="downloadAll">Download All</button> -->
</template>

<script>
import Sites from "../../../apis/Sites";
export default {
  name: "cascades",
  data() {
    return {
      cascades: null,
      cascadesErrors: null,
      cascadeErrors:null,
      serverError: null,
      showModal: false,
      showSpinner: false,
      successMessage: "",
    };
  },
  computed: {
    isLogin() {
      return this.$store.state.isLogin;
    },
    isSuperAdmin() {
      let userRoles = this.$store.state.userRoles;
      let userRole = null;
      userRoles.forEach((role) => {
        if (role.name == "super-admin") {
          userRole = role.name;
        }
      });
      if (userRole) {
        return true;
      } else {
        return false;
      }
    },
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (!vm.isLogin || vm.isSuperAdmin == false) {
        return vm.$router.push(from.path);
      }
    });
  },
  methods: {
    closeModal() {
      return (this.showModal = false);
    },
    getAllCascades() {
      Sites.getAllCascades()
        .then((response) => {
          console.log(response);

          var fileUrl = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");
          fileLink.href = fileUrl;
          fileLink.setAttribute("download", "AllCascades.xlsx");
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch((error) => {
          console.log(error);
        });
    },

    cascadesFile(e) {
      return (this.cascades = e.target.files[0]);
    },
    clearErrors() {
      this.serverError = null;
      this.cascadesErrors = null;

      return;
    },
    submitCascadesSheet() {
      this.serverError = null;
      this.cascadesErrors = null;
       this.cascadeErrors = null;
      var data = {
        cascades: this.cascades,
      };
      this.showSpinner = true;

      Sites.importCascades(data)
        .then((response) => {
          console.log(response.data.message);
          this.successMessage = response.data.message;
          this.showModal = true;
        })
        .catch((error) => {
          if (error.response) {
            if (error.response.status == 500) {
              this.serverError = error.response.data.message;
            } else if (error.response.status == 422) {
              if (error.response.data.errors) {
                this.cascadeErrors = error.response.data.errors.cascades;
              } else if (error.response.data.sheet_errors) {
                this.cascadesErrors = error.response.data.sheet_errors;
              }
            }
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            // console.log(error.response.data);
          } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log("Error", error.message);
          }
          //   console.log(error.config);
        })
        .finally(() => {
          this.showSpinner = false;
          this.cascades = "";

          var sites_sheet = document.getElementById("sites");
          sites_sheet.value = "";
        });
    },
    // downloadAll() {
    //   Sites.downloadAll()
    //     .then((response) => {

    //       var fileUrl = window.URL.createObjectURL(new Blob([response.data]));
    //       var fileLink = document.createElement("a");
    //       fileLink.href = fileUrl;
    //       fileLink.setAttribute("download", "AllSites.xlsx");
    //       document.body.appendChild(fileLink);
    //       fileLink.click();
    //     })
    //     .catch();
    // },
  },
};
</script>

<style lang="scss" scoped>
.index,
.helper-table-container {
  width: 70%;
  margin-left: auto;
  margin-right: auto;
}
.index {
  margin-top: 8em;
}
</style>