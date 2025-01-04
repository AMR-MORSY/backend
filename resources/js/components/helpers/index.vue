<template>
  <div class="container">
    <div class="card index">
      <form @submit.prevent="getNur">
        <div class="row">
        <ul v-if="notFoundErrors">
          <li style="color: red" v-for="error in notFoundErrors" :key="error">
            {{ error }}
          </li>
        </ul>
        <!-- <div class="col-12">
            <div class="form-group">
            <input
              type="radio"
              @change="changePeriod"
              name="period"
              value="week"
              class="form-controler"
              checked
            />
            <label for="week">Week</label>
          </div>

          <div class="form-group">
            <input
              type="radio"
              name="period"
              value="month"
              class="form-controler"
              @change="changePeriod"
            />
            <label for="month">Month</label>
          </div>
         
        </div> -->
        <div class="col-6">
         
          <div class="form-group">
            <select
              name=""
              v-model="week"
              :class="{ 'is-invalid': weekError }"
              class="form-select"
             
            >
            
                 <option value="">--Select Week---</option>
                <option v-for="period in periods" :key="period">
                  {{ period }}
                </option>
         
             
            </select>
                <div v-if="weekError">
              <p style="color: red">{{ weekError }}</p>
            </div>
          </div>
        
        </div>
        <div class="col-6">
          <div class="form-group">
   
            <select
              name=""
              id="year"
              v-model="year"
              :class="{ 'is-invalid': yearError }"
              class="form-select"
            >
              <option value="">--Select Year--</option>
              <option v-for="year in years" :key="year">
                {{ year }}
              </option>
            </select>
            <div v-if="yearError">
              <p style="color: red">{{ yearError }}</p>
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
  </div>
</template>

<script>


export default {
  data() {
    return {
      showSpinner: false,
      years: [],
      year: "",
      yearError: null,
      periods: [],
     
      weekError: null,
      week: "",
     
    
      notFoundErrors: null,
      NUR2G: null,
    };
  },
  name: "index",
  props: ["alarms"],

  methods: {
    getNur() {
      this.weekError = null;
      this.yearError = null;
      this.notFoundErrors = null;
      this.showSpinner=true;
      if (!this.week) {
        this.weekError = "Please select a week";
          this.showSpinner=false;
      }
      if (!this.year) {
        this.yearError = "Please select a year";
           this.showSpinner=false;
      }
      if (this.year && this.week) {
        let data = {
         
          week: this.week,
         
          year: this.year,
        };
        console.log(data);

        if (this.alarms == "NUR") {
             this.showSpinner=false;
          this.$router.push(
            `/nur/statestics/${this.week}/${this.year}`
          );
        } else {
             this.showSpinner=false;
           this.$router.push(
            `/energy/statestics/${this.week}/${this.year}`
          );
         
          // 
        }
      }
    },
    mountFormData() {
      for (var i = 1; i <= 52; i++) {
        this.periods.push(i);
      }
      for (var i = 2022; i <= 2050; i++) {
        this.years.push(i);
      }
    },
   
  },
  mounted() {
    this.mountFormData();
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