<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-12 mt-5">
        <Fieldset>
          <template #legend>{{ site_code }}-{{ site_name }} </template>
          <div class="form">
            <form @submit.prevent="updateModification">
              <div class="row mt-5 p-5">
                <div class="col-12 col-sm-6 col-lg-3">
                  <label for="Subcontrator">Subcontrator:</label>
                  <select
                    class="form-select"
                    id="Subcontrator"
                    v-model="subcontractor"
                    :class="{ 'is-invalid': subcontractorError }"
                  >
                    <option
                      :value="subcontractor"
                      v-for="subcontractor in subcontractors"
                      :key="subcontractor"
                    >
                      {{ subcontractor }}
                    </option>
                  </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                  <label for="status">Status:</label>

                  <select
                    class="form-select"
                    id="status"
                    v-model="status"
                    :class="{ 'is-invalid': statusError }"
                  >
                    <option
                      :value="status"
                      v-for="status in status_options"
                      :key="status"
                    >
                      {{ status }}
                    </option>
                  </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                  <label for="Requesters">Requesters:</label>
                  <select
                    v-model="requester"
                    id="Requesters"
                    class="form-select"
                    :class="{ 'is-invalid': requesterError }"
                  >
                    <option
                      :value="requester"
                      v-for="requester in requester_options"
                      :key="requester"
                    >
                      {{ requester }}
                    </option>
                  </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                  <label for="Projects">Projects:</label>

                  <select
                    class="form-select"
                    aria-label="Default select example"
                    v-model="project"
                    id="Projects"
                    :class="{ 'is-invalid': projectError }"
                  >
                    <option
                      v-for="project in project_options"
                      :value="project"
                      :key="project"
                    >
                      {{ project }}
                    </option>
                  </select>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                  <label for="request">Request Date:</label>

                  <Datepicker
                    v-model="request_date"
                    placeholder="Request Date"
                    required
                    id="request"
                  />
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                  <label for="finish">Finish Date:</label>

                  <Datepicker
                    v-model="finish_date"
                    placeholder="Finish Date:"
                    id="finish"
                  />
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                  <label for="materials">Cost</label>
                  <input
                    type="number"
                    v-model="cost"
                    class="form-control"
                    id="materials"
                  />
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                  <label for="materials">Materials:</label>
                  <textarea
                    v-model="materials"
                    class="form-control"
                    id="materials"
                    cols="3"
                    rows="3"
                  ></textarea>
                </div>
                <div class="col-12 col-lg-6">
                  <label id="Action">Action:</label>
                  <textarea
                    v-model="action"
                    id="Action"
                    rows="5"
                    cols="60"
                    class="form-control"
                    :class="{ 'is-invalid': actionError }"
                  >
                  </textarea>
                </div>
                <div class="col-12 col-lg-3 mt-5">
                  <Button
                    label="Back"
                    type="submit"
                    @click.prevent="goBack"
                    class="p-button-raised p-button-warning"
                    style="color: white"
                  />
                </div>
                <div class="col-12 col-lg-3 mt-5">
                  <Button
                    label="Update"
                    type="submit"
                    class="p-button-raised p-button-warning"
                    style="color: white"
                  />
                </div>
              </div>
            </form>
          </div>
        </Fieldset>
      </div>
    </div>
  </div>
  <Toast />
</template>

<script>
import Modifications from "../../../apis/Modifications";
import allInstances from "../../../apis/Api";
export default {
  data() {
    return {
      site_name: null,
      site_code: null,
      subcontractor: null,
      subcontractorError: false,
      cost: null,

      subcontractors: [
        "OT",
        "Alandick",
        "Tri-Tech",
        "Siatnile",
        "Merc",
        "GP",
        "MBV",
        "Systel",
        "TELE-TECH",
        "SAG",
        "LM",
        "MERG",
      ],
      request_date: null,
      request_dateError: false,
      requester: null,
      requesterError: false,
      requester_options: [
        "Acquisition",
        "Civil Team",
        "Maintenance",
        "Radio",
        "Transmission",
        "rollout",
        "GA",
        "Sharing team",
      ],
      project: null,
      projectError: false,
      project_options: [
        "Normal Modification",
        "LTE",
        "Critical repair",
        "Repair",
        "LDN",
        "Retrofitting",
        "Adding sec",
        "NTRA",
        "Sharing",
        "L2600",
      ],
      status: null,
      statusError: false,
      status_options: ["waiting D6", "done", "in progress"],
      finish_date: null,

      action: null,
      actionError: false,
      materials: null,
    };
  },
  name: "UpdateModification",
  props: ["id"],
  emits: ["displayNoneSpinner"],
  watch: {
    id() {
      this.getModificationDetails();
    },
  },
  computed: {
    isLogin() {
      return this.$store.getters.isLogin;
    },
    token() {
      return this.$store.getters.token;
    },
  },
  mounted() {
    this.getModificationDetails();
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (!vm.isLogin) {
        return vm.$router.push("/user/login");
      }
    });
  },

  methods: {
    goBack() {
      this.$router.go(-1);
    },

    getModificationDetails() {
      this.$store.dispatch("displaySpinnerPage", false);
      allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
      // Modifications.getModificationDetails(this.id)
      allInstances.Api.get(`/modifications/details/${this.id}`)
        .then((response) => {
          console.log(response);
          this.site_code = response.data.details.site_code;
          this.subcontractor = response.data.details.subcontractor;

          this.site_name = response.data.details.site_name;

          this.request_date = response.data.details.request_date;
          this.requester = response.data.details.requester;
          // requester_options=response.data.details.;
          this.project = response.data.details.project;
          // project_options: null,
          this.status = response.data.details.status;
          // status_options: null,
          this.finish_date = response.data.details.finish_date;
          this.action = response.data.details.action;
          this.cost = response.data.details.cost;
          this.materials = response.data.details.materials;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },
    updateModification() {
      this.subcontractorError = false;
      this.request_dateError = false;
      this.requesterError = false;
      this.statusError = false;
      this.projectError = false;
      this.actionError = false;
      if (!this.subcontractor) {
        this.subcontractorError = true;
      }

      if (!this.project) {
        this.projectError = true;
      }
      if (!this.status) {
        this.statusError = true;
      }
      if (!this.action) {
        this.actionError = true;
      }
      if (!this.request_date) {
        this.request_dateError = true;
      }
      if (!this.requester) {
        this.requesterError = true;
      }
      if (
        this.requester &&
        this.subcontractor &&
        this.action &&
        this.request_date &&
        this.project &&
        this.status
      ) {
        this.$store.dispatch("displaySpinnerPage", false);
        let data = {
          id: this.id,
          site_code: this.site_code,
          site_name: this.site_name,
          subcontractor: this.subcontractor,
          requester: this.requester,
          request_date: this.request_date,
          finish_date: this.finish_date,
          cost: this.cost,
          project: this.project,
          status: this.status,
          action: this.action,
          materials: this.materials,
        };
        console.log(data);
        // Modifications.updateModification(data)
        allInstances.Api.defaults.headers[
          "Authorization"
        ] = `Bearer ${this.token}`;
        allInstances.Api.post("/modifications/update", data)
          .then((response) => {
            console.log(response);

            this.$toast.add({
              severity: "success",
              summary: "Success Message",
              detail: "Updated Successfully",
              life: 3000,
            });
          })
          .catch((error) => {
            console.log(error);
            if (error.response.status == 422) {
              let errors = error.response.data.errors;

              if (errors.siteCode) {
                errors.siteCode.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.siteName) {
                errors.siteName.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.subcontractor) {
                errors.subcontractor.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.project) {
                errors.project.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.status) {
                errors.status.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.requester) {
                errors.requester.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.request_date) {
                errors.request_date.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.finish_date) {
                errors.finish_date.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.cost) {
                errors.cost.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.materials) {
                errors.materials.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.action) {
                errors.action.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
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
      }
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep(.p-fieldset) {
  position: relative;

  .p-fieldset-legend {
    width: 25%;
    color: white;
    text-align: center;
    position: absolute;
    top: 20px;
    left: 50px;
    z-index: 2;
    background-color: rgba($color: gray, $alpha: 1);
  }

  .p-button {
    background-color: #79589f !important;
    border-color: #79589f !important;
  }
  .p-inputtext {
    border-color: #79589f;
  }
  .p-inputtext:focus {
    box-shadow: 0px 0px 3px 2px #79589f !important;
  }

  .p-inputtextarea {
    resize: none;
    width: 100%;
  }
}

// $dp__border_radius: 30px !default;

.form-select option:hover {
  background-color: #79589f !important;
}
.dp__theme_light {
  --dp-text-color: #79589f;

  --dp-icon-color: #79589f;

  --dp-border-color: #79589f;
}

.form {
  margin-top: 50px;
  width: 100%;
  border: 1px solid black;
  border-radius: 5px;
}
@media screen and (max-width: 576px) {
}
</style>