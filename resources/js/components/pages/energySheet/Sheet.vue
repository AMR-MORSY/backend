<template>
  <div class="container mt-5">
    <div class="card index">
    <form
      id="energysheet"
      @submit.prevent="submitEnergySheet"
      enctype="multipart/form-data"
    >
    
      <div class="row">
        <div class="col-12">
          <div v-if="serverError">
            {{ serverError }}
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-group">
            <select v-model="week" id="weeks" class="form-select">
              <option value="">select week</option>

              <option v-for="week in weeks" :key="week">{{ week }}</option>
            </select>
            <div v-if="weekErrors">
              <ul>
                <li v-for="error in weekErrors" style="color: red" :key="error">
                  {{ error }}
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
            <select v-model="year" class="form-select">
              <option value="">select year</option>

              <option v-for="year in years" :key="year">{{ year }}</option>
            </select>
            <div v-if="yearErrors">
              <ul>
                <li v-for="error in yearErrors" style="color: red" :key="error">
                  {{ error }}
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="power">Energy Sheet:</label>
            <input
              type="file"
              name="energy_sheet"
              class="form-control"
              id="energy_sheet"
              @change="energySheetFile"
            />
            <div v-if="energySheetErrors">
              <ul>
                <li
                  v-for="error in energySheetErrors"
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
            class="btn"
              style="background-color:#79589f;color:white;"
          >
            <span> Submit</span>
          </spinner-button>
        </div>
      </div>
    </form>
    </div>
    <div class="helper-table-container">
      <helper-table v-if="sheetValidationErrors">
        <template #header>
          <th scope="col">Row</th>
          <th scope="col">Attribute</th>
          <th scope="col">Errors</th>
          <th scope="col">Values</th>
        </template>
        <template #body>
          <tr
            style="background-color: white; color: red"
            v-for="error in sheetValidationErrors"
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
                <li>Site Name:{{ error.values["Alarm Name"] }}</li>
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
</template>

<script>
import axios from "axios";
import spinnerButton from "../../helpers/spinnerButton.vue";
import Energy from "../../../apis/Energy";

export default {
  components: { spinnerButton },
  data() {
    return {
      weeks: [],
      years: [],
      year: "",
      week: "",
      energySheet: null,

      sheetValidationErrors: null,

      yearErrors: null,

      weekErrors: null,

      energySheetErrors: null,
      serverError: null,

      successMessage: null,

      showModal: false,
      showSpinner: false,
    };
  },
  name: "Sheet",

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
      } else {
        vm.getEnergySheetIndex();
      }
    });
  },

  mounted() {},
  methods: {
    getEnergySheetIndex() {
      this.serverError = null;
      this.yearErrors = null;
      this.weekErrors = null;

      Energy.getEnergySheetIndex()
        .then((response) => {
          this.weeks = response.data.weeks;
          this.years = response.data.years;
        })
        .catch((error) => {
          if (error.response.status == 500) {
            this.serverError = "internal Server Error";
          }
        });
    },

    energySheetFile(e) {
      return (this.energySheet = e.target.files[0]);
    },

    closeModal() {
      return (this.showModal = false);
    },

    submitEnergySheet() {
      this.weekErrors = null;
      this.serverError = null;
      this.yearErrors = null;
      this.sheetValidationErrors = null;
      var data = {
        energy_sheet: this.energySheet,
        week: this.week,
        year: this.year,
      };
      this.showSpinner = true;

      Energy.submitEnergySheet(data)
        .then((response) => {
          console.log(response.data.message);
          this.successMessage = response.data.message;
          this.showModal = true;
        })
        .catch((error) => {
          if (error.response) {
            console.log(error.response);
            if (error.response.status == 500) {
              this.serverError = error.response.data.message;
            }
            if (error.response.status == 422) {
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                if (errors.week) {
                  this.weekErrors = errors.week;
                }
                if (errors.year) {
                  this.yearErrors = errors.year;
                }
                if (errors.energy_sheet) {
                  this.energySheetErrors = errors.energy_sheet;
                }
              } else if (error.response.data.sheet_errors) {
                this.sheetValidationErrors = error.response.data.sheet_errors;
              }
            }
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
          this.week = "";
          this.year = "";
          var energy_sheet = document.getElementById("energy_sheet");
          energy_sheet.value = "";
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.index,
.helper-table-container {
  width: 70%;
  margin-left: auto;
  margin-right: auto;
  padding: 2rem;
}
.index {
  margin-top: 6em;
  .header{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    p{
      font-size: 2rem;
      font-weight: 900;
      color: darkmagenta;
    }
  }
}
</style>