<template>
  <div class="container mt-5">
    <div class="row mt-5">
      <div class="col-md-1"></div>
      <div class="col-12 col-md-10 mt-5 mb-3">
        <div class="card">
          <TabView class="myTabView" ref="tabview4">
            <TabPanel>
              <template #header>
                <span class="header">{{ siteName }}</span>
              </template>

              <div class="row site-details">
                <div class="col-6 col-md-4">
                  <div class="input-field">
                    <h6>Site code:</h6>
                    <InputText
                      type="text"
                      id="siteCode"
                      disabled
                      v-model="siteCode"
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Site Name:</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="siteName"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>BSC</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="BSC"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>2G</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="cell2G"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>3G</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="cell3G"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>4G</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="cell4G"
                      disabled
                    />
                  </div>
                </div>

                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>RNC</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="RNC"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>OZ</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="operationZone"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Type</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="type"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Category</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="category"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Severity</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="severity"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Office</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="office"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Sharing</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="sharing"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Host</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="host"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Gest</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="gest"
                      disabled
                    />
                  </div>
                </div>
                 <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Nodal Code</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="nodalCode"
                      disabled
                    />
                  </div>
                </div>
                 <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Nodal Name</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="nodalName"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-6 col-md-4">
                  <div class="form-group">
                    <h6>Status</h6>
                    <InputText
                      type="text"
                      class="p-inputtext-focuse"
                      id="siteCode"
                      v-model="status"
                      disabled
                    />
                  </div>
                </div>
              </div>
              <div class="row mt-5 px-5 buttons">
                <div class="col-6 col-sm-4 col-md-4 my-3">
                  <Button
                    label="Modifications"
                    @click="gotToSiteModifications"
                    class="p-button-raised p-button-warning"
                  />
                </div>
                <div class="col-6 col-sm-2 col-md-2 my-3">
                  <Button
                    label="NUR"
                    @click="getSiteNUR"
                    class="p-button-raised p-button-secondary"
                  />
                </div>
                <div class="col-6 col-sm-3 col-md-3 my-3">
                  <Button
                    label="Update"
                    @click="goToSiteUpdate"
                    class="p-button-raised p-button-help"
                  />
                </div>
                <div class="col-6 col-sm-3 col-md-3 my-3 ">
                  <div
                    class="speed-dial"
                  
                  >
                    <SpeedDial :model="items" direction="right" :tooltipOptions="{position: 'right'}" :transitionDelay="80" showIcon="pi pi-bell" hideIcon="pi pi-times" buttonClass="p-button-outlined"  >
                     
                    </SpeedDial>
                  </div>
                </div>
              </div>
            </TabPanel>
            <TabPanel>
              <template #header>
                <span class="header">Cascades</span>
                <template v-if="countCascades">
                  <Badge :value="countCascades"></Badge>
                </template>
              </template>
              <template v-if="countCascades">
                <DataTable
                  :value="cascades"
                  responsiveLayout="scroll"
                  class="p-datatable-sm"
                  :paginator="true"
                  :rows="5"
                  stripedRows
                  v-model:selection="selectedSite"
                  selectionMode="single"
                  dataKey="cascade_code"
                  @row-select="onRowSelect"
                >
                  <Column selectionMode="single"></Column>
                  <Column field="cascade_code" header="Site Code"></Column>
                  <Column field="cascade_name" header="Site Name"></Column>
                   <Column field="category" header="Site Category"></Column>
                  <Column field="countCascades" header="Cascades"></Column>
                </DataTable>
              </template>
              <template v-else>
                <div>
                  <p>No Cascades</p>
                </div>
              </template>

              <Button
                label="Update"
                @click="goToUpdateCascadesPage"
                class="p-button-rounded p-button-help"
              />
            </TabPanel>
            <TabPanel>
              <template #header>
                <span class="header">Indirect Cascades</span>
                <template v-if="countIndirectCascades">
                  <Badge :value="countIndirectCascades"></Badge>
                </template>
              </template>
              <template v-if="countIndirectCascades">
                <DataTable
                  :value="indirectCascades"
                  responsiveLayout="scroll"
                  class="p-datatable-sm"
                  :paginator="true"
                  :rows="5"
                  stripedRows
                  v-model:selection="selectedSite"
                  selectionMode="single"
                  dataKey="cascade_code"
                  @row-select="onRowSelect"
                >
                  <Column selectionMode="single"></Column>
                  <Column field="cascade_code" header="Site Code"></Column>
                  <Column field="cascade_name" header="Site Name"></Column>
                     <Column field="category" header="Site Category"></Column>
                </DataTable>
              </template>
              <template v-else>
                <div>
                  <p>No Cascades</p>
                </div>
              </template>
            </TabPanel>
          </TabView>
        </div>
      </div>
      <div class="col-md-1"></div>
    </div>
  </div>

</template>

<script>
import Sites from "../../../apis/Sites";
import siteNURTable from "../NUR/siteNURTable.vue";
import NUR from "../../../apis/NUR";
import Energy from "../../../apis/Energy";
import SiteAlarmsTable from "../energySheet/SiteAlarmsTable.vue";
export default {
  data() {
    return {
      siteName: null,
      siteCode: null,
      cell2G: null,
      cell3G: null,
      cell4G: null,
      BSC: null,
      RNC: null,
      operationZone: null,
      type: null,
      category: null,
      office: null,
      sharing: null,
      host: null,
      severity: null,
      status:null,
      gest: null,
      cascades: null,
      indirectCascades: null,
      selectedSite: null,
      nodalCode:null,
      nodalName:null,
      data:null,
      NUR2G: [],
      NUR3G: [],
      NUR4G: [],
      items: [
        {
          label: "Power Alarms",
          icon: 'pi pi-bolt',
          command: () => {
           this.getSitePowerAlarms();
          },
        },
         {
          label: "Gen Alarms",
          icon: 'pi pi-exclamation-circle',
          command: () => {
            this.getSiteGenAlarms();
          },
        },
         {
          label: "HT Alarms",
          icon: 'pi pi-power-off',
          command: () => {
             this.getSiteHTAlarms();
          },
        },
      ],
    };
  },
  props: ["site_code"],
  emits: ["displayNoneSpinner"],
  name: "Details",
  components: {
    siteNURTable,
    SiteAlarmsTable,
  },
  watch: {
    site_code() {
      this.getSiteDetails();
    },
  },

  computed: {
    countCascades() {
      if (this.cascades == null || this.cascades.length == 0) {
        return null;
      } else {
        return this.cascades.length;
      }
    },
    countIndirectCascades() {
      if (this.indirectCascades == null || this.indirectCascades.length == 0) {
        return null;
      } else {
        return this.indirectCascades.length;
      }
    },
  },
  mounted() {
    this.getSiteDetails();
  },
  methods: {
    getSiteNUR() {
       this.$store.dispatch("displaySpinnerPage", false);
      let data = {
        site_code: this.site_code,
      };
      NUR.getSiteNUR(data)
        .then((response) => {
          console.log(response);

          this.NUR2G = response.data.NUR2G;
          this.NUR3G = response.data.NUR3G;
          this.NUR4G = response.data.NUR4G;

          if (
            this.NUR2G.length == 0 &&
            this.NUR3G.length == 0 &&
            this.NUR4G.length == 0
          ) {
            this.$confirm.require({
              message: "This site did not make NUR",
              header: "Confirmation",
              icon: "pi pi-exclamation-triangle",
              position: "top",
              rejectIcon: "",
            });
          } else {
            this.$dialog.open(siteNURTable, {
              props: {
                header: this.siteName,
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
                NUR3G: this.NUR3G,
                NUR2G: this.NUR2G,
                NUR4G: this.NUR4G,
                site_code: this.siteCode,
                site_name: this.siteName,
              },
            });
          }
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
         this.$store.dispatch("displaySpinnerPage", true);
        });
    },
    getSiteDetails() {
       this.$store.dispatch("displaySpinnerPage",false);
      Sites.getSiteDetails(this.site_code)
        .then((response) => {
          console.log(response);
          this.siteName = response.data.site.site_name;
          this.siteCode = response.data.site.site_code;
          this.type = response.data.site.type;
          this.severity = response.data.site.severity;
          this.sharing = response.data.site.sharing;
          this.status=response.data.site.status;
          this.host = response.data.site.host;
          this.gest = response.data.site.gest;
          this.cell2G = response.data.site["2G_cells"];
          this.cell3G = response.data.site["3G_cells"];
          this.cell4G = response.data.site["4G_cells"];
          this.BSC = response.data.site.BSC;
          this.RNC = response.data.site.RNC;
          this.office = response.data.site.office;
          this.operationZone = response.data.site.oz;
          this.category = response.data.site.category;
          this.cascades = response.data.cascades;
          this.indirectCascades = response.data.indirectCascades;
          this.nodalCode=response.data.site.nodal_code;
          this.nodalName=response.data.site.nodal_name
          this.data={
            site_code:this.siteCode,
          }
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
           this.$store.dispatch("displaySpinnerPage",true);
        });
    },
    openDialog(){
         this.$dialog.open(SiteAlarmsTable, {
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

          onClose: (options) => {
            this.$store.dispatch("siteAlarms", null);
          },
        });


    },
    onRowSelect() {
      this.$router.push(`/sites/details/${this.selectedSite.cascade_code}`);
    },
    gotToSiteModifications() {
      this.$router.push(
        `/modifications/sitemodifications/${this.siteCode}/${this.siteName}`
      );
    },
    goToSiteUpdate() {
      this.$router.push(`/sites/update/${this.siteCode}`);
    },

    goToUpdateCascadesPage() {
      this.$router.push(`/sites/cascades/update/${this.siteCode}`);
    },
    getSitePowerAlarms()
    {
      this.$store.dispatch("displaySpinnerPage",false);
      Energy.getSitePowerAlarms(this.data).then((response)=>{

        if(response.data.alarms.length>0)
        {
           this.$store.dispatch("siteAlarms",{"alarmName":"power","alarmData":response.data.alarms});
          this.openDialog();

        }
        else{
            this.$toast.add({ severity: 'error', summary: 'Invalid Code', detail: `No Alarms`,life: 3000 });

        }
         
        
        console.log(response)
      }).catch((error)=>{
       
        if(error.response.status==422)
        {
          if(error.response.data.errors.site_code)
          {
            let site_codeErrors=error.response.data.errors.site_code;
            site_codeErrors.forEach(element => {
               this.$toast.add({ severity: 'error', summary: 'Invalid Code', detail: `${element}`,life: 3000 });
              
            });
          }
        }

      }).finally(()=>{
        this.$store.dispatch("displaySpinnerPage",true);

      })

    },
    getSiteHTAlarms()
    {
         this.$store.dispatch("displaySpinnerPage",false);
      Energy.getSiteHighTempAlarms(this.data).then((response)=>{
         if(response.data.alarms.length>0)
        {
           this.$store.dispatch("siteAlarms",{"alarmName":"highTemp","alarmData":response.data.alarms});
          this.openDialog();

        }
        else{
            this.$toast.add({ severity: 'error', summary: 'Invalid Code', detail: `No Alarms`,life: 3000 });

        }
         
        
      
      }).catch((error)=>{
        if(error.response.status==422)
        {
          if(error.response.data.errors.site_code)
          {
            let site_codeErrors=error.response.data.errors.site_code;
            site_codeErrors.forEach(element => {
               this.$toast.add({ severity: 'error', summary: 'Invalid Code', detail: `${element}`,life: 3000 });
              
            });
          }
        }

      }).finally(()=>{
        this.$store.dispatch("displaySpinnerPage",true);

      })


    },
    getSiteGenAlarms()
    {
       this.$store.dispatch("displaySpinnerPage",false);
      Energy.getSiteGenAlarms(this.data).then((response)=>{
          if(response.data.alarms.length>0)
        {
          this.$store.dispatch("siteAlarms",{"alarmName":"gen","alarmData":response.data.alarms});
          this.openDialog();
        }
        else{
            this.$toast.add({ severity: 'error', summary: 'No Data Found', detail: `No Alarms`,life: 3000 });

        }
         
          
        
      
      }).catch((error)=>{
        if(error.response.status==422)
        {
          if(error.response.data.errors.site_code)
          {
            let site_codeErrors=error.response.data.errors.site_code;
            site_codeErrors.forEach(element => {
               this.$toast.add({ severity: 'error', summary: 'Invalid Code', detail: `${element}`,life: 3000 });
              
            });
          }
        }
      }).finally(()=>{
        this.$store.dispatch("displaySpinnerPage",true);

      })

    }
  },
};
</script>

<style lang="scss" scoped>
::v-deep.myTabView {
  .p-tabview-panel {
    color: #79589f;
  }
  .p-inputtext {
    border-color: #79589f !important;
    text-align: center;
    color: #79589f;
    font-weight: 500;
  }
  .p-inputtext:focus {
    box-shadow: 0px 0px 3px 2px #79589f !important;
  }
  .p-badge {
    background-color: #79589f;
    margin-left: 5px;
  }

  .header {
    color: #79589f;
  }
}
.site-details,
.buttons {
  border: 1px solid #79589f;
  border-radius: 5px;
  padding: 3rem 0;
}
.buttons {
  padding: 1rem 0;
}
.display-none {
  display: none;
}
::v-deep.speed-dial {
 
 
  .p-speeddial-direction-right {
   
    .p-speeddial-button {
    
      bottom: 5px;
       width: 55px;
       height: 55px;
       left: 0;
    }
   
  }
}
</style>