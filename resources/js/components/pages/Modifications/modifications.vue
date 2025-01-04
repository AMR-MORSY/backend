<template>
  <section id="analysis">
    <div class="container">
      <div class="card index">
        <div v-if="serverError">
          <p style="color: red">{{ serverError }}</p>
        </div>
        <form @submit.prevent="submitFilterForm">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="form-group">
                <select class="form-select" @change="submitColumn" id="column">
                  <option value="">Filter By:</option>
                  <option v-for="column in columns" :key="column">
                    {{ column }}
                  </option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <select
                  class="form-select"
                  @change="submitColumnValue"
                  id="columnValue"
                >
                  <option value=""></option>
                  <option v-for="value in columnValues" :key="value">
                    {{ value }}
                  </option>
                </select>
              </div>
            </div>

            <div class="col-12 mt-3">
              <div class="button-container">
                <button
                  class="btn"
                  :disabled="disabled"
                  type="submit"
                  style="background-color: #79589f; color: white"
                >
                  submit
                </button>
                <!-- <button class="btn btn-primary"[disabled]='!isDataFound'  (click)="downloadsites()">Excel Export</button> -->
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script>
import Modifications from "../../../apis/Modifications";
import allInstances from "../../../apis/Api";

export default {
  data() {
    return {
      column: null,
      columnValue: null,
      columns: null,
      columnValues: null,
      status: null,
      subcontractor: null,
      project: null,
      requester: null,
      serverError: null,
      display: true,
    };
  },
  name: "modifications",
  computed: {
    disabled() {
      if (!this.column || !this.columnValue) {
        return true;
      }
      if (this.column && this.columnValue) {
        return false;
      }
    },
    token()
    {
      return this.$store.getters.token;
    },
    isLogin()
    {
      return this.$store.getters.isLogin;
    }
  },
  mounted() {
    this.getModificationAnalysis();
  },
   beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (!vm.isLogin) {
        return vm.$router.push("/user/login");
      }
    });
  },
  methods: {
    submitColumn(e) {
      this.column = e.target.value;

      if (this.column == "status") {
        this.columnValues = this.status;
      } else if (this.column == "subcontractor") {
        this.columnValues = this.subcontractor;
      } else if (this.column == "requester") {
        this.columnValues = this.requester;
      } else if (this.column == "project") {
        this.columnValues = this.project;
      }
    },
    submitColumnValue(e) {
      this.columnValue = e.target.value;
    },
    getModificationAnalysis() {
      // Modifications.getModificationAnalysis()
      allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
      allInstances.Api.get("/modifications/analysis")
        .then((response) => {
          this.status = response.data.index.status;
          this.subcontractor = response.data.index.subcontractor;
          this.project = response.data.index.project;
          this.requester = response.data.index.requester;
          this.columns = ["status", "subcontractor", "requester", "project"];
        })
        .catch((error) => {
          if (error.response.status == 500) {
            this.serverError = error.response.data.message;
          }
        });
    },
    submitFilterForm() {
      let data = {
        columnName: this.column,
        columnValue: this.columnValue,
      };
      console.log(data);
      this.$router.push(
        `/modifications/index/${this.column}/${this.columnValue}`
      );
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
  .header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    p {
      font-size: 2rem;
      font-weight: 900;
      color: darkmagenta;
    }
  }
}

@media screen and (max-width: 575px) {
  #analysis {
    .container {
      .form-container {
        width: 100%;
      }

      .button-container {
        width: 100%;
      }
    }
  }
}

@media (min-width: 576px) and (max-width: 767px) {
  #analysis {
    .container {
      .form-container {
        width: 100%;
      }

      .button-container {
        width: 100%;
      }
    }
  }
}
@media (min-width: 768px) and (max-width: 991px) {
  #analysis {
    .container {
      .form-container {
        width: 100%;
      }

      .button-container {
        width: 100%;

        display: flex;
        justify-content: space-around;
        align-items: center;
      }
    }
  }
}
@media (min-width: 992px) and (max-width: 1199px) {
}
@media (min-width: 1200px) and (max-width: 1500px) /* extra large windows*/ {
}
</style>