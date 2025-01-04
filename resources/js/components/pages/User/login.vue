<template>
  <div class="container mb-3">
    <div class="row">
      <div class="col-12 col-lg-4"></div>
      <div class="col-12 col-lg-4">
        <div class="form-container">
          <Card style="border: 1px solid #79589f; border-radius: 5px">
            <template #title>
              <p class="p-card-title text-center" style="color: #79589f">
                Login
              </p>
            </template>
            <template #content>
              <form @submit.prevent="submitLoginForm">
                <div class="row">
                  <div class="col-12">
                    <div class="field w-100">
                      <span class="p-float-label">
                        <InputText
                          id="inputtext"
                          class="w-100"
                          type="text"
                          v-model="form.email"
                          :class="{ 'p-invalid': emailError }"
                        />
                        <label for="inputtext">Email</label>
                      </span>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="field w-100 mt-4">
                      <span class="p-float-label">
                        <Password
                          toggleMask
                          v-model="form.password"
                          id="password"
                          class="w-100"
                          :feedback="false"
                          :class="{ 'p-invalid': passwordError }"
                        ></Password>
                        <label for="password">Password</label>
                      </span>
                    </div>
                  </div>
                  <div class="col-12 mt-2">
                    <div
                      class="d-flex w-100 align-items-center justify-content-flex-start mt-3"
                    >
                      <router-link to="/user/resetPassword"
                        >Forgot Password?</router-link
                      >
                    </div>
                  </div>

                  <div class="col-12">
                    <Button
                      label="Sign in"
                      class="w-100 mt-4"
                      type="submit"
                      style="background-color: #79589f"
                    />
                  </div>
                </div>
              </form>
            </template>
          </Card>
        </div>
      </div>
      <div class="col-12 col-lg-4"></div>
    </div>
  </div>
  <Toast />
</template>

<script >
import axios from "axios";
import User from "../../../apis/User";

export default {
  data() {
    return {
      form: {
        email: null,
        password: null,
      },

      passwordError: null,

      emailError: null,
    };
  },
  name: "login",

  computed: {
    isLogin() {
      return this.$store.getters.isLogin;
    },
  },

  beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (vm.isLogin) {
        return vm.$router.push(from.path);
      }
    });
  },
  methods: {
    submitLoginForm() {
      this.passwordError = null;
      this.emailError = null;

      if (!this.form.email) {
        this.emailError = "Email is required";
      }
      if (!this.form.password) {
        this.passwordError = "Password is required";
      }
      if (!this.passwordError && !this.emailError) {
        this.$store.dispatch("displaySpinnerPage", false);
        User.login(this.form)
          .then((response) => {
            sessionStorage.setItem(
              "User",
              JSON.stringify(response.data.user_data)
            );
            this.$store.dispatch("userData", response.data.user_data);

            this.$router.push({
              path: "/home",
            });
          })
          .catch((error) => {
            if (error.response) {
              console.log(error.response);
              if (error.response.status == 401) {
                this.$toast.add({
                  severity: "error",
                  summary: "Error Message",
                  detail: error.response.data.message,
                  life: 6000,
                });
              }

              if (error.response.status == 422) {
                this.$toast.add({
                  severity: "error",
                  summary: "Error Message",
                  detail: "invalid credentials",
                  life: 6000,
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

<style lang="scss"  scoped>
.form-container {
  margin-top: 6em;
}

::v-deep(.p-password input) {
  width: 100%;
  border-color: #79589f;
}
::v-deep(.p-password input:focus) {
  border-color: #79589f !important;
  box-shadow: 0px 0px 3px 2px #79589f !important;
}
.p-button {
  background-color: #79589f !important;
  border-color: #79589f !important;
}
.p-button:focus {
  box-shadow: 0px 0px 3px 2px #79589f !important ;
}
.p-inputtext {
  border-color: #79589f;
}
.p-inputtext:focus {
  box-shadow: 0px 0px 3px 2px #79589f !important;
  border-color: #79589f !important ;
}
.p-inputtext:hover {
  border-color: #79589f !important ;
}

.bd-placeholder-img {
  font-size: 1.125rem;
  text-anchor: middle;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
}

@media (min-width: 768px) {
  .bd-placeholder-img-lg {
    font-size: 3.5rem;
  }
}
</style>