<template>
  <div>
    <Card class="mt-5" style="background-color: #c3b1e1">
      <template #title>
        <p style="font-size: 16px; color: white; pading: 0; text-align: center">
          Cairo North
        </p>
      </template>
      <template #content>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <Card>
              <template #title>
                <p style="font-size: 16px; pading: 0; text-align: center">
                  Subsystem
                </p>
              </template>
              <template #content>
                <DataTable
                  :value="subsystem"
                  responsiveLayout="scroll"
                  class="p-datatable-sm"
                  stripedRows
                  :paginator="true"
                  :rows="5"
                >
                  <Column selectionMode="single"></Column>
                  <Column field="subsystem" header="Subsystem"></Column>
                  <Column field="value" header="NUR_C" sortable></Column>
                </DataTable>
                <!-- <Chart type="doughnut" :data="subsystem" :plugins="plugins"  :options="lightOptions" /> -->
              </template>
            </Card>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <Card>
              <template #title>
                <p style="font-size: 16px; pading: 0; text-align: center">
                  Generator Statestics
                </p>
                <div class="row">
                  <div class="col-3">
                    <img
                      src="../../logos/Orange_logo.svg"
                      class="w-75"
                      alt=""
                      v-tooltip.right="'Get Tickets'"
                      style="cursor: pointer"
                      @click="getORGGenTickets"
                    />
                  </div>
                  <div class="col-3">
                    <img
                      src="../../logos/Etisalat_eand_Logo_AR.svg"
                      class="w-100"
                      alt=""
                      v-tooltip.right="'Get Tickets'"
                      style="cursor: pointer"
                      @click="getETGenTickets"
                    />
                  </div>
                  <div class="col-3">
                    <img
                      src="../../logos/Vodafone_2017_logo.svg"
                      class="w-100"
                      alt=""
                      v-tooltip.right="'Get Tickets'"
                      style="cursor: pointer"
                      @click="getVFGenTickets"
                    />
                  </div>
                  <div class="col-3">
                    <img
                      src="../../logos/rent-sign-svgrepo-com.svg"
                      class="w-75"
                      alt=""
                      v-tooltip.right="'Get Tickets'"
                      style="cursor: pointer"
                      @click="getRentedGenTickets"
                    />
                  </div>
                </div>
              </template>
              <template #content>
                <Chart
                  type="bar"
                  :data="generatorStatestics"
                  :plugins="plugins"
                  :options="lightOptions"
                />
              </template>
            </Card>
          </div>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <Card>
              <template #title>
                <p style="font-size: 16px; pading: 0; text-align: center">
                 Nodals & VIP 
                </p>
              </template>
              <template #content>
                <div class="row">
                  <div class="col-4">
                    <div class="w-100">
                         <img src="../../logos/kisspng-radio-vip-fm-romania-service-sales-vip-fm-98-spons-超市vip-5af1f24b2cd0c2.8140047415258056431836.png"  @click="getVipSitesNUR" class="w-100" style="cursor:pointer;" alt="">

                    </div>

                  </div>
                  <div class="col"></div>
                  <div class="col-4">
                      <div class="w-100 nodal " @click="getNodalSitesNUR">
                         <img src="../../logos/c67d66dd354d921a8c6652ebaf82d8bc.svg"  class="w-75"  alt="">

                    </div>

                  </div>
                </div>
              
              </template>
            </Card>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-6 mt-2">
            <TopSites :zoneNUR="cairoNorthTopNUR" @siteNUR="getSiteNUR">
              <template #header> Top Sites NUR </template>
              <template #columns>
                <Column field="siteName" header="Name"></Column>
                <Column field="NUR" header="NUR" sortable></Column>
              </template>
            </TopSites>
          </div>
          <div class="col-12 col-md-6 mt-2">
            <TopSites :zoneNUR="cairoNorthRepeatedSites" @siteNUR="getSiteNUR">
              <template #header> Repeated Sites </template>
              <template #columns>
                <Column field="siteName" header="Name"></Column>
                <Column field="count" header="Count" sortable></Column>
              </template>
            </TopSites>
          </div>
        </div>
      
      </template>
    </Card>
  </div>
  <!-- <DynamicDialog :key="cairoNorthNur" /> -->
</template>

<script>
import TopSites from "./TopSites.vue";
import ChartDataLabels from "chartjs-plugin-datalabels";
import siteNURTable from "./siteNURTable.vue";
import NURTickets from "./NURTickets.vue";
import NUR from "../../../apis/NUR";
import VipsOrNodals from "../NUR/VipsOrNodals.vue";
import allInstances from "../../../apis/Api";
export default {
  data() {
    return {
      subsystem: null,
      generatorStatestics: null,
      accessStatesitcs: null,

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
    };
  },
  components: {
    TopSites,
    siteNURTable,
    VipsOrNodals,
    NURTickets
  },
  props: [
    "cairoNorthSubsystem",
    "year",
    "cairoNorthTopNUR",
    "cairoNorthGen",
    "cairoNorthRepeatedSites",
    "cairoNorthAccessStatesitcs",
    "week",
  ],
  name: "CairoNorth",
   computed:{
      token() {
      return this.$store.getters.token;
    },
     isLogin()
    {
      return this.$store.getters.isLogin;
    }

  },
  mounted() {
    this.mountSubsystemTable();

    if (this.cairoNorthGen) {
      this.genStatestics(this.cairoNorthGen);
    }
    if (this.cairoNorthAccessStatesitcs) {
      this.accessStatesitcs = {
        labels: ["NUR", "Total Tickets", "Access Tickets"],
        datasets: [
          {
            data: [this.cairoNorthAccessStatesitcs.NUR, ,],
            label: "NUR",
            backgroundColor: "#7F00FF",
          },
          {
            data: [, this.cairoNorthAccessStatesitcs.totalTickets],
            label: "Total Tickets",
            backgroundColor: "#C3B1E1",
          },
          {
            data: [, , this.cairoNorthAccessStatesitcs.accessTickets],
            label: "Access Tickets",
            backgroundColor: "#800080",
          },
        ],
      };
    }
  },
  methods: {
    getORGGenTickets() {
      if (this.cairoNorthGen["ORG"].tickets.length > 0) {
        this.$dialog.open(NURTickets, {
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
            allTickets: this.cairoNorthGen["ORG"].tickets,
          },
        });
      }
    },
    getETGenTickets() {
      if (this.cairoNorthGen["ET"].tickets.length > 0) {
        this.$dialog.open(NURTickets, {
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
            allTickets: this.cairoNorthGen["ET"].tickets,
          },
        });
      }
    },
    getVFGenTickets() {
      if (this.cairoNorthGen["VF"].tickets.length > 0) {
        this.$dialog.open(NURTickets, {
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
            allTickets: this.cairoNorthGen["VF"].tickets,
          },
        });
      }
    },
    getRentedGenTickets() {
      if (this.cairoNorthGen["Rented"].tickets.length > 0) {
        this.$dialog.open(NURTickets, {
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
            allTickets: this.cairoNorthGen["Rented"].tickets,
          },
        });
      }
    },
    mountSubsystemTable() {
      if (this.cairoNorthSubsystem) {
        let subssytems = Object.keys(this.cairoNorthSubsystem);
        let values = Object.values(this.cairoNorthSubsystem);
        let subssytemsLength = subssytems.length;
        let tableData = [];
        for (var i = 0; i < subssytemsLength; i++) {
          var subsystemObj = {
            subsystem: subssytems[i],
            value: values[i],
          };
          tableData.push(subsystemObj);
        }
        this.subsystem = tableData;
      }
    },
   
    getSiteNUR(event) {
      console.log(event.NUR3G);
      this.$dialog.open(siteNURTable, {
        props: {
          header: event.NUR3G[0].problem_site_name,
          style: {
            width: "75vw",
          },
          breakpoints: {
            "960px": "75vw",
            "640px": "90vw",
          },
        },
        // templates: {
        //   footer: () => {
        //     return [
        //       h(Button, {
        //         label: "No",
        //         icon: "pi pi-times",
        //         onClick: () => dialogRef.close({ buttonType: "No" }),
        //         class: "p-button-text",
        //       }),
        //       h(Button, {
        //         label: "Yes",
        //         icon: "pi pi-check",
        //         onClick: () => dialogRef.close({ buttonType: "Yes" }),
        //         autofocus: true,
        //       }),
        //     ];
        //   },
        // },
        data: {
          NUR3G: event.NUR3G,
          NUR2G: event.NUR2G,
          NUR4G: event.NUR4G,
        },
      });
    },

    getVipSitesNUR() {
      this.$store.dispatch("displaySpinnerPage", false);
      let sites = [];
        allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;

      allInstances.Api.get(`/Nur/vip/week/Cairo North/${this.week}/${this.year}`)

      // NUR.getVipSitesWeeklyNUR("Cairo North", this.week, this.year)
        .then((response) => {
          if (response.data.sites.length > 0) {
            sites = response.data.sites;
            this.$dialog.open(VipsOrNodals, {
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
                sites: sites,
              },
            });
          } else {
            this.$store.dispatch(
              "dialogMessage",
              "Great !!! VIP sites did not make NUR this Week"
            );
            this.$store.dispatch("displayDialog", true);
          }
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },
    getNodalSitesNUR() {
      this.$store.dispatch("displaySpinnerPage", false);
      let sites = [];
         allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;

      allInstances.Api.get(`/Nur/nodal/week/Cairo North/${this.week}/${this.year}`)

      // NUR.getNodalSitesWeeklyNUR("Cairo North", this.week, this.year)
        .then((response) => {
          if (response.data.sites.length > 0) {
            sites = response.data.sites;
            this.$dialog.open(VipsOrNodals, {
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
                sites: sites,
              },
            });
          } else {
            this.$store.dispatch(
              "dialogMessage",
              "Great !!! Nodal sites did not make NUR this Week"
            );
            this.$store.dispatch("displayDialog", true);
          }
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },

    genStatestics(statestics) {
      let count = [];
      let NUR = [];
      count.push(statestics.ET.count);
      count.push(statestics.ORG.count);
      count.push(statestics.Rented.count);
      count.push(statestics.VF.count);
      NUR.push(statestics.ET.nur);
      NUR.push(statestics.ORG.nur);
      NUR.push(statestics.Rented.nur);
      NUR.push(statestics.VF.nur);

      let chart = {
        labels: ["ET", "ORG", "Rented", "VF"],
        datasets: [
          {
            data: count,
            label: "Count",
            backgroundColor: "#7F00FF",
          },
          {
            data: NUR,
            label: "NUR",
            backgroundColor: "#C3B1E1",
          },
        ],
      };

      this.generatorStatestics = chart;
    },
  },
};
</script>

<style lang="scss" scoped>
.nodal{
  position: relative;
  margin-top: 10px;
  cursor: pointer;
}
.nodal::after{
  content: "Nodals";
  position: absolute;
  right: 0;
  bottom: -5px;
  color: black;
  margin-right: 20px;
 
  font-weight: 600;

}
</style>