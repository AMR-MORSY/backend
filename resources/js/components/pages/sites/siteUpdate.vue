<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-12 mt-5 mb-5">
        <Card>
          <template #content>
            <div class="form">
              <form @submit.prevent="updateSite">
                <div class="row p-5">
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="siteCode">Site Code:</label>
                    <input
                      type="text"
                      id="siteCode"
                      v-model="site_code"
                      class="form-control"
                      :class="{ 'is-invalid': siteCodeError }"
                    />
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="siteName">Site Name:</label>
                    <input
                      type="text"
                      id="siteName"
                      v-model="site_name"
                      class="form-control"
                      :class="{ 'is-invalid': siteNameError }"
                    />
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="BSC">BSC:</label>
                    <input
                      type="text"
                      id="BSC"
                      v-model="BSC"
                      class="form-control"
                    />
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="RNC">RNC:</label>
                    <input
                      type="text"
                      id="RNC"
                      v-model="RNC"
                      class="form-control"
                    />
                  </div>

                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="office">Office:</label>
                    <input
                      type="text"
                      id="office"
                      v-model="office"
                      class="form-control"
                    />
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="category">Category:</label>
                    <select
                      id="category"
                      v-model="category"
                      class="form-select"
                    >
                      <option></option>
                      <option
                        :value="categ"
                        v-for="categ in categoryOptions"
                        :key="categ"
                      >
                        {{ categ }}
                      </option>
                    </select>
                  </div>

                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="type">Type:</label>
                    <select id="type" v-model="type" class="form-select">
                      <option></option>
                      <option
                        :value="type"
                        v-for="type in typeOptions"
                        :key="type"
                      >
                        {{ type }}
                      </option>
                    </select>
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="severity">severity:</label>
                    <select
                      id="severity"
                      v-model="severity"
                      class="form-select"
                    >
                      <option></option>
                      <option
                        :value="severity"
                        v-for="severity in severityOptions"
                        :key="severity"
                      >
                        {{ severity }}
                      </option>
                    </select>
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="sharing">sharing:</label>
                    <select id="sharing" v-model="sharing" class="form-select">
                      <option></option>
                      <option
                        :value="sharing"
                        v-for="sharing in sharingOptions"
                        :key="sharing"
                      >
                        {{ sharing }}
                      </option>
                    </select>
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="host">host:</label>
                    <select
                      id="host"
                      v-model="host"
                      class="form-select"
                      :class="{ 'is-invalid': hostError }"
                    >
                      <option></option>
                      <option
                        :value="host"
                        v-for="host in hostOptions"
                        :key="host"
                      >
                        {{ host }}
                      </option>
                    </select>
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="gest">gest:</label>
                    <select
                      id="gest"
                      v-model="gest"
                      class="form-select"
                      :class="{ 'is-invalid': gestError }"
                    >
                      <option></option>
                      <option
                        :value="gest"
                        v-for="gest in gestOptions"
                        :key="gest"
                      >
                        {{ gest }}
                      </option>
                    </select>
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="oz">Operation Zone:</label>
                    <select id="oz" v-model="oz" class="form-select">
                      <option></option>
                      <option :value="oz" v-for="oz in ozOptions" :key="oz">
                        {{ oz }}
                      </option>
                    </select>
                  </div>
                   <div class="col-12 col-sm-6 col-lg-3">
                    <label for="oz">Status:</label>
                    <select id="oz" v-model="status" class="form-select">
                      <option></option>
                      <option :value="status" v-for="status in statusOptions" :key="status">
                        {{ status }}
                      </option>
                    </select>
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="2G">2G Cells:</label>
                    <input
                      type="number"
                      id="2G"
                      class="form-control"
                      v-model="cells2G"
                    />
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="3G">3G Cells:</label>
                    <input
                      type="number"
                      id="3G"
                      class="form-control"
                      v-model="cells3G"
                    />
                  </div>
                  <div class="col-12 col-sm-6 col-lg-3">
                    <label for="4G">4G Cells:</label>
                    <input
                      type="number"
                      id="4G"
                      class="form-control"
                      v-model="cells4G"
                    />
                  </div>
                  <div class="col-12">
                    <div class="row mt-5">
                      <div class="col-6 col-lg-4">
                        <Button
                          label="back"
                          type="button"
                          class="back"
                          @click="goBack"
                        />
                      </div>
                      <div class="col-6 col-lg-4">
                        <Button
                          label="insert"
                          type="submit"
                          class="insert"
                          style="color: white"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </div>
  <Toast />
</template>

<script>
import Sites from "../../../apis/Sites";
export default {
  data() {
    return {
      site_code: null,
      siteCodeError: false,
      site_name: null,
      siteNameError: false,
      BSC: null,

      RNC: null,

      office: null,

      type: null,

      typeOptions: ["Shelter", "Micro", "Outdoor"],
      statusOptions:["On Air","Off Air"],
      status:null,
      category: null,

      categoryOptions: ["Normal", "BSC", "NDL", "LDN", "VIP + NDL", "VIP"],
      severity: null,

      severityOptions: ["Bronze", "Silver", "Golden"],
      sharing: null,
      sharingError: false,
      sharingOptions: ["Yes", "No"],
      host: null,
      hostError: false,
      hostOptions: ["VF", "OG", "ET", "WE"],
      gest: null,
      gestError: false,
      gestOptions: ["VF", "OG", "ET", "WE"],
      oz: null,
      ozOptions: ["Cairo South", "Cairo East", "Cairo North", "Giza"],
      cells2G: null,
      cells3G: null,
      cells4G: null,
    };
  },
  name: "siteUpdate",
  props: ["siteCode"],
  emits: ["displayNoneSpinner"],
  watch: {
    siteCode() {
      this.getSiteDetails();
    },
  },

  mounted() {
    this.getSiteDetails();
  },

  methods: {
    goBack() {
      this.$router.go(-1);
    },
    getSiteDetails() {
        this.$store.dispatch("displaySpinnerPage", false);

      Sites.getSiteDetails(this.siteCode)
        .then((response) => {
          console.log(response);
          this.site_name = response.data.site.site_name;
          this.site_code = response.data.site.site_code;
          this.type = response.data.site.type;
          this.severity = response.data.site.severity;
          this.sharing = response.data.site.sharing;
          this.status=response.data.site.status;
          this.host = response.data.site.host;
          this.gest = response.data.site.gest;
          this.cells2G = response.data.site["2G_cells"];
          this.cells3G = response.data.site["3G_cells"];
          this.cells4G = response.data.site["4G_cells"];
          this.BSC = response.data.site.BSC;
          this.RNC = response.data.site.RNC;
          this.office = response.data.site.office;
          this.oz = response.data.site.oz;
          this.category = response.data.site.category;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },

    updateSite() {
      this.siteCodeError = false;
      this.siteNameError = false;
      this.sharingError = false;
      this.hostError = false;
      this.gestError = false;

      if (!this.site_code) {
        this.siteCodeError = true;
      }

      if (!this.site_name) {
        this.siteNameError = true;
      }

      if (this.sharing == "Yes" && !this.host) {
        this.hostError = true;
        this.sharingError = true;
      }
      if (this.sharing == "Yes" && !this.gest) {
        this.gestError = true;
        this.sharingError = true;
      }

      if (this.site_code && this.site_name && !this.sharingError) {
     this.$store.dispatch("displaySpinnerPage", false);
        let data = {
          site_code: this.site_code,
          site_name: this.site_name,
          BSC: this.BSC,
          RNC: this.RNC,
          office: this.office,
          severity: this.severity,
          category: this.category,
          type: this.type,
          sharing: this.sharing,
          oz: this.oz,
          host: this.host,
          gest: this.gest,
          "2G_cells": this.cells2G,
          "3G_cells": this.cells3G,
          "4G_cells": this.cells4G,
          status:this.status
        };
        console.log(data);
        Sites.updateSite(data)
          .then((response) => {
            console.log(response);

            this.$toast.add({
              severity: "success",
              summary: "Success Message",
              detail: "Updated Successfully",
              life: 3000,
            });
            this.site_name = response.data.site.site_name;
            this.site_code = response.data.site.site_code;
            this.type = response.data.site.type;
            this.severity = response.data.site.severity;
            this.sharing = response.data.site.sharing;
            this.host = response.data.site.host;
            this.gest = response.data.site.gest;
            this.cells2G = response.data.site["2G_cells"];
            this.cells3G = response.data.site["3G_cells"];
            this.cells4G = response.data.site["4G_cells"];
            this.BSC = response.data.site.BSC;
            this.RNC = response.data.site.RNC;
            this.office = response.data.site.office;
            this.oz = response.data.site.oz;
            this.category = response.data.site.category;
          })
          .catch((error) => {
            console.log(error);
            if (error.response.status == 422) {
              let errors = error.response.data.errors;

              if (errors.site_code) {
                errors.site_code.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.site_name) {
                errors.site_name.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.BSC) {
                errors.BSC.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.RNC) {
                errors.RNC.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.office) {
                errors.office.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.type) {
                errors.type.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.category) {
                errors.category.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.host) {
                errors.host.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.gest) {
                errors.gest.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.sharing) {
                errors.sharing.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.severity) {
                errors.severity.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors.oz) {
                errors.oz.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors["2G_cells"]) {
                errors["2G_cells"].forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors["3G_cells"]) {
                errors["3G_cells"].forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Failed",
                    detail: element,
                    life: 3000,
                  });
                });
              }
              if (errors["4G_cells"]) {
                errors["4G_cells"].forEach((element) => {
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
.p-button.back,
.p-button.back:hover {
  background-color: var(--pink-500);
  border-color: var(--pink-500);
}
.p-button.insert,
.p-button.insert:hover {
  background-color: var(--teal-400);
  border-color: var(--teal-400);
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

// $dp__border_radius: 30px !default;

.form-select option:hover {
  background-color: #79589f !important;
}
.dp__theme_light {
  --dp-text-color: red;

  --dp-icon-color: #79589f;

  --dp-border-color: #79589f;
}

.form {
  width: 100%;
  border: 1px solid black;
  border-radius: 5px;
}
@media screen and (max-width: 576px) {
}
</style>