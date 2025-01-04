<template>
  <div class="container mt-5" v-if="isNURAvailable">
    <Card>
      <template #content>
        <div class="row">
          <div class="col"></div>
          <div class="col-10">
            <div class="title">
              <p>Week:{{ week }}</p>
            </div>
          </div>
          <div class="col"></div>
          <div class="col-12 col-md-6 col-lg-4 mt-5">
            <Card>
              <template #title>
                <p style="font-size: 16px; pading: 0; text-align: center">
                  2G NUR
                </p>
              </template>
              <template #content>
                <Chart
                  type="bar"
                  :data="zones2GNUR"
                  :options="lightOptions"
                  :plugins="plugins"
                />
              </template>
              <template #footer>
                <p>Cairo 2G NUR={{ cairo2GNUR }}</p>
              </template>
            </Card>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-5">
            <Card>
              <template #title>
                <p style="font-size: 16px; pading: 0; text-align: center">
                  3G NUR
                </p>
              </template>
              <template #content>
                <Chart
                  type="bar"
                  :data="zones3GNUR"
                  :options="lightOptions"
                  :plugins="plugins"
                />
              </template>
              <template #footer>
                <p>Cairo 3G NUR={{ cairo3GNUR }}</p>
              </template>
            </Card>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-4 mt-5">
            <Card>
              <template #title>
                <p style="font-size: 16px; pading: 0; text-align: center">
                  4G NUR
                </p>
              </template>
              <template #content>
                <Chart
                  type="bar"
                  :data="zones4GNUR"
                  :options="lightOptions"
                  :plugins="plugins"
                />
              </template>
              <template #footer>
                <p>Cairo 4G NUR={{ cairo4GNUR }}</p>
              </template>
            </Card>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-4 mt-5">
            <Card>
              <template #title>
                <p style="font-size: 16px; pading: 0; text-align: center">
                  Combined NUR
                </p>
                <div class="row">
                  <div class="col"></div>
                  <div class="col-10">
                    <div
                      style="
                        display: flex;
                        align-items: center;
                        justify-content: center;
                      "
                    >
                      <div
                        class="w-50"
                        style="
                          display: flex;
                          align-items: center;
                          justify-content: center;
                        "
                      >
                        <img
                          src="../../logos/week-icon.svg"
                          @click="getCairoNUR_CYearlyAnalysis"
                          style="cursor: pointer"
                          alt=""
                          class="w-25"
                          v-tooltip.right="'Weekly Analysis'"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col"></div>
                </div>
              </template>
              <template #content>
                <Chart
                  type="bar"
                  :data="zonesCombinedNUR"
                  :options="lightOptions"
                  :plugins="plugins"
                />
              </template>
            </Card>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-5">
            <Card>
              <template #title>
                <p style="font-size: 16px; pading: 0; text-align: center">
                  Response With Access
                </p>
              </template>
              <template #content>
                <Chart
                  type="bar"
                  :data="zonesResponseWithAccess"
                  :options="lightOptions"
                  :plugins="plugins"
                />
              </template>
            </Card>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-5">
            <Card>
              <template #title>
                <p style="font-size: 16px; pading: 0; text-align: center">
                  Response Without Access
                </p>
              </template>
              <template #content>
                <Chart
                  type="bar"
                  :data="zonesResponseWithoutAccess"
                  :options="lightOptions"
                  :plugins="plugins"
                />
              </template>
            </Card>
          </div>
          <div class="col-12 mt-5">
           
                <div class="row">
                  <div class="col-6 col-sm-6 col-md-3">
                    <div
                      class="transmission w-100"
                      style="cursor: pointer"
                      @click="getCairoMWWeeklyNUR"
                    >
                      <img
                        src="../../logos/favpng_falesia-systems-sp-o-o-microwave-transmission-mini-link-rectifier-xpic.png"
                        alt=""
                        class="w-50 m-auto d-block"
                      />
                    </div>
                  </div>
                  <div class="col-6 col-sm-6 col-md-3">
                    <div
                      class="gen w-100"
                      style="cursor: pointer"
                      @click="getCairoGenWeeklyNUR"
                    >
                      <img
                        src="../../logos/33kva-cat-silent-diesel-generator-cat-de33gc.jpg"
                        alt=""
                        class="w-50 m-auto d-block"
                      />
                    </div>
                  </div>
                  <div class="col-6 col-sm-6 col-md-3">
                    <div
                      class="BTS w-100"
                      style="cursor: pointer"
                      @click="getCairoMWWeeklyNUR"
                    >
                      <img
                        src="../../logos/Nortel-GSM-800012000.jpg"
                        alt=""
                        class="w-50 m-auto d-block"
                      />
                    </div>
                  </div>
                 
                  <div class="col-6 col-sm-6 col-md-3">
                    <div
                      class="power w-100"
                      style="cursor: pointer"
                      @click="getCairoPowerWeeklyNUR"
                    >
                      <img
                        src="../../logos/power-plant-icon.svg"
                        alt=""
                        class="w-50 m-auto d-block"
                      />
                    </div>
                  </div>
                   <div class="col-6 col-sm-6 col-md-3">
                    <div
                      class="modifications w-75"
                      style="cursor: pointer"
                      @click="getCairoMWWeeklyNUR"
                    >
                      <img
                        src="../../logos/clipart3239793.png"
                        alt=""
                        class="w-50 m-auto d-block"
                      />
                    </div>
                  </div>
                </div>
           
          </div>
        </div>
      </template>
    </Card>

    <CairoSouth
      :cairoSouthTopNUR="cairoSouthTopNUR"
      :cairoSouthRepeatedSites="cairoSouthRepeatedSites"
      :cairoSouthGen="cairoSouthGen"
      :cairoSouthSubsystem="cairoSouthSubsystem"
      :cairoSouthAccessStatesitcs="cairoSouthAccessStatesitcs"
      :week="week"
      :year="year"
    />
    <CairoEast
      :cairoEastTopNUR="cairoEastTopNUR"
      :cairoEastRepeatedSites="cairoEastRepeatedSites"
      :cairoEastGen="cairoEastGen"
      :cairoEastSubsystem="cairoEastSubsystem"
      :cairoEastAccessStatesitcs="cairoEastAccessStatesitcs"
      :week="week"
      :year="year"
    />
    <CairoNorth
      :cairoNorthTopNUR="cairoNorthTopNUR"
      :cairoNorthRepeatedSites="cairoNorthRepeatedSites"
      :cairoNorthGen="cairoNorthGen"
      :cairoNorthSubsystem="cairoNorthSubsystem"
      :cairoNorthAccessStatesitcs="cairoNorthAccessStatesitcs"
      :week="week"
      :year="year"
    />
    <Giza
      :gizaTopNUR="gizaTopNUR"
      :gizaRepeatedSites="gizaRepeatedSites"
      :gizaGen="gizaGen"
      :gizaSubsystem="gizaSubsystem"
      :gizaAccessStatesitcs="gizaAccessStatesitcs"
      :week="week"
      :year="year"
    />
  </div>
  <template v-else>
    <div class="container mt-5">
      <div class="row mt-5">
        <div class="col-12 col-md-4"></div>
        <div class="col-12 col-md-4 mt-5">
          <transition-group name="fade-bounce" appear>
            <template v-if="weekErrors">
              <div class="errors card">
                <p v-for="error in weekErrors" :key="error">
                  {{ error }}
                </p>
              </div>
            </template>
            <template v-if="yearErrors">
              <div class="errors card">
                <p v-for="error in yearErrors" :key="error">
                  {{ error }}
                </p>
              </div>
            </template>
            <template v-if="monthErrors">
              <div class="errors card">
                <p v-for="error in monthErrors" :key="error">
                  {{ error }}
                </p>
              </div>
            </template>
            <template v-if="notFoundErrors">
              <div class="errors card">
                <p v-for="error in notFoundErrors" :key="error">
                  {{ error }}
                </p>
                <div>
                  <Button
                    label="Back"
                    class="p-button-danger"
                    @click="this.$router.go(-1)"
                  />
                </div>
              </div>
            </template>
          </transition-group>
        </div>
        <div class="col-12 col-md-4"></div>
      </div>
    </div>
  </template>
</template>

<script>
import ChartDataLabels from "chartjs-plugin-datalabels";
import NUR from "../../../apis/NUR";
import TopSites from "./TopSites.vue";
import siteNURTable from "./siteNURTable.vue";
import CairoSouth from "./CairoSouth.vue";
import CairoEast from "./CairoEast.vue";
import CairoNorth from "./CairoNorth.vue";
import Giza from "./Giza.vue";
import CairoTX from "./CairoTX.vue";
import CairoYearlyAnalysis from "./CairoYearlyAnalysis.vue";
import CairoGen from "../NUR/CairoGen.vue";

import CairoMainPower from './CairoMainPower.vue';
import allInstances from "../../../apis/Api";

export default {
  data() {
    return {
      weekErrors: null,

      monthErrors: null,
      yearErrors: null,
      notFoundErrors: null,
      isNURAvailable: false,
      zones2GNUR: null,
      zones3GNUR: null,
      zones4GNUR: null,
      zonesCombinedNUR: null,
      cairo3GNUR: null,
      cairo2GNUR: null,
      cairo4GNUR: null,
      cairoCombinedNUR: null,
      cairoSouthSubsystem: null,
      cairoEastSubsystem: null,
      cairoNorthSubsystem: null,
      gizaSubsystem: null,
      zonesResponseWithAccess: null,
      zonesResponseWithoutAccess: null,
      lightOptions: {
        plugins: {
          legend: {
            labels: {
              color: "red",
            },
          },
          datalabels: {
            anchor: "end",
            color: "red",
          },
        },
      },
      plugins: [ChartDataLabels],
      cairoSouthGen: null,
      cairoEastGen: null,
      cairoNorthGen: null,
      gizaGen: null,
      cairoSouthTopNUR: null,
      cairoEastTopNUR: null,
      cairoNorthTopNUR: null,
      gizaTopNUR: null,
      cairoSouthRepeatedSites: null,
      gizaRepeatedSites: null,
      cairoNorthRepeatedSites: null,
      cairoEastRepeatedSites: null,
      cairoSouthAccessStatesitcs: null,
      cairoEastAccessStatesitcs: null,
      cairoNorthAccessStatesitcs: null,
      gizaAccessStatesitcs: null,
    };
  },
  name: "NURStatestics",
  beforeMount() {
    this.getNUR();
  },
  props: ["week", "year"],

  watch: {
    week() {
      this.getNUR();
    },
  },
  computed:{
      token() {
      return this.$store.getters.token;
    },
     isLogin()
    {
      return this.$store.getters.isLogin;
    }

  },
  components: {
    TopSites,
    siteNURTable,
    CairoSouth,
    CairoEast,
    CairoNorth,
    Giza,
    CairoTX,
    CairoYearlyAnalysis,
    CairoGen,
    CairoMainPower,
  },
   beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (!vm.isLogin) {
        return vm.$router.push("/user/login");
      }
    });
  },

  methods: {
    Responses(zoneExceed, zoneWith) {
      let exceed = [];
      let withinSLA = [];
      zoneExceed.forEach((element) => {
        exceed.push(element);
      });

      zoneWith.forEach((element) => {
        withinSLA.push(element);
      });
      let response = {
        exceed: exceed,
        withinSLA: withinSLA,
      };
      return response;
    },
    getNUR() {
      this.$store.dispatch("displaySpinnerPage", false);
      let data = {
        week: this.week,

        year: this.year,
      };


      // NUR.getNur(data)
        allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
     
      allInstances.Api.get(`/Nur/show/${data.week}/${data.year}`)
        .then((response) => {
          console.log(response);
          let NUR = response.data.NUR;
          this.isNURAvailable = true;
          this.zones2GNUR = {
            labels: Object.keys(NUR.NUR2G.zonesNUR2G),

            datasets: [
              {
                label: "2G NUR",
                backgroundColor: "#7F00FF",
                data: Object.values(NUR.NUR2G.zonesNUR2G),
                borderWidth: 1,
              },
              {
                label: "No.tickets",
                backgroundColor: "#C3B1E1",
                data: Object.values(NUR.NUR2G.zonesTotalNumTickets),
                borderWidth: 1,
              },
            ],
          };
          this.cairo2GNUR = NUR.NUR2G.cairoNUR2G;
          this.zones3GNUR = {
            labels: Object.keys(NUR.NUR3G.zonesNUR3G),
            datasets: [
              {
                label: "3G NUR",
                backgroundColor: "#7F00FF",
                data: Object.values(NUR.NUR3G.zonesNUR3G),
                borderWidth: 1,
              },
              {
                label: "No.tickets",
                backgroundColor: "#C3B1E1",
                data: Object.values(NUR.NUR3G.zonesTotalNumTickets),
                borderWidth: 1,
              },
            ],
          };
          this.cairo3GNUR = NUR.NUR3G.cairoNUR3G;

          this.zones4GNUR = {
            labels: Object.keys(NUR.NUR4G.zonesNUR4G),
            datasets: [
              {
                label: "4G NUR",
                backgroundColor: "#7F00FF",
                data: Object.values(NUR.NUR4G.zonesNUR4G),
                borderWidth: 1,
              },
              {
                label: "No.tickets",
                backgroundColor: "#C3B1E1",
                data: Object.values(NUR.NUR4G.zonesTotalNumTickets),
                borderWidth: 1,
              },
            ],
          };
          this.cairo4GNUR = NUR.NUR4G.cairoNUR4G;
          this.zonesCombinedNUR = {
            labels: Object.keys(NUR.combined),

            datasets: [
              {
                label: "Combined NUR",
                backgroundColor: "#C3B1E1",
                data: Object.values(NUR.combined),
                borderWidth: 1,
              },
            ],
          };
          this.cairoCombinedNUR = NUR.combined.cairo;

          let zoneExceed = [
            NUR.zonesResponseWithAccess["CAIRO EAST"].exceedSLA,
            NUR.zonesResponseWithAccess["CAIRO SOUTH"].exceedSLA,
            NUR.zonesResponseWithAccess["CAIRO NORTH"].exceedSLA,
            NUR.zonesResponseWithAccess["GIZA"].exceedSLA,
          ];
          let zoneWith = [
            NUR.zonesResponseWithAccess["CAIRO EAST"].withinSLA,
            NUR.zonesResponseWithAccess["CAIRO SOUTH"].withinSLA,

            NUR.zonesResponseWithAccess["CAIRO NORTH"].withinSLA,

            NUR.zonesResponseWithAccess["GIZA"].withinSLA,
          ];
          let resp = this.Responses(zoneExceed, zoneWith);

          this.zonesResponseWithAccess = {
            labels: Object.keys(NUR.zonesResponseWithoutAccess),
            datasets: [
              {
                data: resp.exceed,
                label: "exceed SLA",
                backgroundColor: "#7F00FF",
              },
              {
                data: resp.withinSLA,
                label: "within SLA",
                backgroundColor: "#C3B1E1",
              },
            ],
          };

          zoneExceed = [
            NUR.zonesResponseWithoutAccess["CAIRO EAST"].exceedSLA,
            NUR.zonesResponseWithoutAccess["CAIRO SOUTH"].exceedSLA,
            NUR.zonesResponseWithoutAccess["CAIRO NORTH"].exceedSLA,
            NUR.zonesResponseWithoutAccess["GIZA"].exceedSLA,
          ];
          zoneWith = [
            NUR.zonesResponseWithoutAccess["CAIRO EAST"].withinSLA,
            NUR.zonesResponseWithoutAccess["CAIRO SOUTH"].withinSLA,

            NUR.zonesResponseWithoutAccess["CAIRO NORTH"].withinSLA,

            NUR.zonesResponseWithoutAccess["GIZA"].withinSLA,
          ];
          resp = this.Responses(zoneExceed, zoneWith);

          this.zonesResponseWithoutAccess = {
            labels: Object.keys(NUR.zonesResponseWithAccess),
            datasets: [
              {
                data: resp.exceed,
                label: "exceed SLA",
                backgroundColor: "#7F00FF",
              },
              {
                data: resp.withinSLA,
                label: "within SLA",
                backgroundColor: "#C3B1E1",
              },
            ],
          };

          this.cairoSouthSubsystem = NUR.zonesSubsystem["CAIRO SOUTH"];
          this.cairoEastSubsystem = NUR.zonesSubsystem["CAIRO EAST"];
          this.cairoNorthSubsystem = NUR.zonesSubsystem["CAIRO NORTH"];
          this.gizaSubsystem = NUR.zonesSubsystem["GIZA"];

          this.cairoSouthGen = NUR.zonesGeneratorStatestics["CAIRO SOUTH"];
          this.cairoEastGen = NUR.zonesGeneratorStatestics["CAIRO EAST"];
          this.cairoNorthGen = NUR.zonesGeneratorStatestics["CAIRO NORTH"];
          this.gizaGen = NUR.zonesGeneratorStatestics["GIZA"];

          this.cairoSouthTopNUR = Object.values(NUR.topNUR["CAIRO SOUTH"]);
          this.cairoEastTopNUR = Object.values(NUR.topNUR["CAIRO EAST"]);
          this.cairoNorthTopNUR = Object.values(NUR.topNUR["CAIRO NORTH"]);
          this.gizaTopNUR = Object.values(NUR.topNUR["GIZA"]);

          this.cairoSouthRepeatedSites = Object.values(
            NUR.topRepeated["CAIRO SOUTH"]
          );
          this.cairoEastRepeatedSites = Object.values(
            NUR.topRepeated["CAIRO EAST"]
          );
          this.cairoNorthRepeatedSites = Object.values(
            NUR.topRepeated["CAIRO NORTH"]
          );
          this.gizaRepeatedSites = Object.values(NUR.topRepeated["GIZA"]);

          // this.cairoSouthAccessStatesitcs = {
          //   totalTickets: NUR.NUR3G.zonesTotalNumTickets["CAIRO SOUTH"],
          //   accessTickets:
          //     NUR.NUR3G.zonesNUR3GAccessCountTickets["CAIRO SOUTH"].access,
          //   NUR: NUR.NUR3G.zonesNUR3GAccessNUR["CAIRO SOUTH"].access,
          // };
          // this.cairoNorthAccessStatesitcs = {
          //   totalTickets: NUR.NUR3G.zonesTotalNumTickets["CAIRO NORTH"],
          //   accessTickets:
          //     NUR.NUR3G.zonesNUR3GAccessCountTickets["CAIRO NORTH"].access,
          //   NUR: NUR.NUR3G.zonesNUR3GAccessNUR["CAIRO NORTH"].access,
          // };
          // this.cairoEastAccessStatesitcs = {
          //   totalTickets: NUR.NUR3G.zonesTotalNumTickets["CAIRO EAST"],
          //   accessTickets:
          //     NUR.NUR3G.zonesNUR3GAccessCountTickets["CAIRO EAST"].access,
          //   NUR: NUR.NUR3G.zonesNUR3GAccessNUR["CAIRO EAST"].access,
          // };
          // this.gizaAccessStatesitcs = {
          //   totalTickets: NUR.NUR3G.zonesTotalNumTickets["GIZA"],
          //   accessTickets:
          //     NUR.NUR3G.zonesNUR3GAccessCountTickets["GIZA"].access,
          //   NUR: NUR.NUR3G.zonesNUR3GAccessNUR["GIZA"].access,
          // };
        })
        .catch((error) => {
          console.log(error);
          this.isNURAvailable = false;
          if (error.response.status == 422) {
            let errors = error.response.data.errors;
            if (errors.week) {
              this.weekErrors = [];
              errors.week.forEach((element) => {
                this.weekErrors.push(element);
              });
            }
            if (errors.month) {
              this.monthErrors = [];
              errors.month.forEach((element) => {
                this.monthErrors.push(element);
              });
            }
            if (errors.year) {
              this.yearErrors = [];
              errors.year.forEach((element) => {
                this.yearErrors.push(element);
              });
            }
          } else if (error.response.status == 404) {
            this.notFoundErrors = error.response.data.errors;
          }
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },
    getCairoMWWeeklyNUR() {
      this.$store.dispatch("displaySpinnerPage", false);
        allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
      allInstances.Api.get(`/Nur/cairo/weekly/MWNUR/${this.week}/${this.year}`)
      // NUR.getCairoMWWeeklyNUR(this.week, this.year)
        .then((response) => {
          console.log(response);
          let siteData = [];
          let sites = response.data.sites;
          sites.forEach((site) => {
            siteData.push(site.site_data);
          });
          this.$dialog.open(CairoTX, {
            props: {
              style: {
                width: "75vw",
              },
              breakpoints: {
                "960px": "75vw",
                "640px": "90vw",
              },
              modal: true,
            },

            data: {
              sites: siteData,
              tickets: response.data.tickets,
              statestics: response.data.statestics,
            },
          });
        })
        .catch((error) => {})
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },

    getCairoGenWeeklyNUR() {
      this.$store.dispatch("displaySpinnerPage", false);
        allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
      // NUR.getCairoGenWeeklyNUR(this.week, this.year)
      allInstances.Api.get(`/Nur/cairo/weekly/GenNUR/${this.week}/${this.year}`)
        .then((response) => {
          console.log(response);
          let siteData = [];
          let sites = response.data.sites;
          sites.forEach((site) => {
            siteData.push(site.site_data);
          });
          this.$dialog.open(CairoGen, {
            props: {
              style: {
                width: "75vw",
              },
              breakpoints: {
                "960px": "75vw",
                "640px": "90vw",
              },
              modal: true,
            },

            data: {
              sites: siteData,
              tickets: response.data.tickets,
              statestics: response.data.statestics,
            },
          });
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },

    getCairoNUR_CYearlyAnalysis() {
      this.$store.dispatch("displaySpinnerPage", false);
         allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
      // NUR.cairoNUR_CYearlyAnalysis(this.year)
      allInstances.Api.get(`/Nur/cairo/yearly/NUR_C/${this.year}`)
        .then((response) => {
          let cairo = response.data.NUR_C_yearly.cairo;
          let zones = response.data.NUR_C_yearly.zones;
          this.$dialog.open(CairoYearlyAnalysis, {
            props: {
              style: {
                width: "75vw",
              },
              breakpoints: {
                "960px": "75vw",
                "640px": "90vw",
              },
              modal: true,
            },

            data: {
              cairo: cairo,
              zones: zones,
            },
          });
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },
    getCairoPowerWeeklyNUR()
    { this.$store.dispatch("displaySpinnerPage", false);
       allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
    // NUR.getCairoPowerWeeklyNUR(this.week,this.year)
    allInstances.Api.get(`/Nur/cairo/weekly/PowerNUR/${this.week}/${this.year}`)
    .then((response)=>{
      console.log(response)
       let siteData = [];
          let sites = response.data.sites;
          sites.forEach((site) => {
            siteData.push(site.site_data);
          });
          this.$dialog.open( CairoMainPower, {
            props: {
              style: {
                width: "75vw",
              },
              breakpoints: {
                "960px": "75vw",
                "640px": "90vw",
              },
              modal: true,
            },

            data: {
              sites: siteData,
              tickets: response.data.tickets,
              statestics: response.data.statestics,
            },
          });
    }).catch((error)=>{

    }).finally(()=>{
      this.$store.dispatch("displaySpinnerPage", true);

    })

    },
  },
};
</script>

<style lang="scss" scoped>
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
.title {
  margin-top: 50px;
  width: 100%;
  p {
    width: 100%;
    text-align: center;
    font-weight: 500;
  }
}

.errors {
  padding: 3rem;
  p {
    color: red;
    text-align: center;
  }
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
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
.transmission,
.gen,
.BTS,
.modifications,
.power {
  position: relative;
  margin-bottom: 10px;
}
.modifications,
.power {
  padding-top: 10px;
  margin: auto;
}
.BTS::after {
  content: "Node";
}
.gen::after {
  content: "Generators";
}
.modifications::after {
  content: "Modifications";
}
.power::after {
  content: "Power";
}
.gen::after,
.transmission::after,
.BTS::after,
.modifications::after,
.power::after {
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);

  font-weight: 700;

  color: black;
}
// .BTS::after{
//   left: 18px;
// }
.modifications::after,
.power::after {
  bottom: -20px;
  // left: -20px;
}
.transmission::after {
  content: "Transmission";
}
</style>