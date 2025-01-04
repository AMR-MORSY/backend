<template>
 
  <div class="container mb-3">
    <div class="row">
      <div class="col-12 col-lg-4"></div>
      <div class="col-12 col-lg-4">
        <div class="form-container">
          <Card style="border: 1px solid #79589f; border-radius: 5px">
            <template #title class="p-card-title">
              <p class="text-center" style="color: #79589f">Register</p>
            </template>
            <template #content>
              <form @submit.prevent="submitRegisterForm">
                
                <div class="row">
                  <div class="col-12">
                    <div class="field w-100">
                      <span class="p-float-label">
                        <InputText
                          id="inputtext"
                          class="w-100"
                          type="text"
                          v-model="form.name"
                          :class="{ 'p-invalid': nameError }"
                        />
                        <label for="inputtext">Name</label>
                      </span>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="field w-100 mt-4">
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
                          required
                          :feedback="true"
                          :class="{ 'p-invalid': passwordError }"
                        >
                          <template #header>
                            <h6>Pick a password</h6>
                          </template>
                          <template #footer="sp">
                            {{ sp.level }}
                            <Divider />
                            <p class="mt-2">Suggestions</p>
                            <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
                              <li>At least one lowercase</li>
                              <li>At least one uppercase</li>
                              <li>At least one from [@$!%*?&]</li>
                              <li>Minimum 8 characters</li>
                            </ul>
                          </template></Password
                        >
                        <label for="password">Password</label>
                      </span>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="w-100 mt-4">
                      <span class="p-float-label">
                        <Password
                          toggleMask
                          v-model="form.password_confirmation"
                          id="password"
                          class="w-100"
                          :feedback="false"
                          required
                          :class="{ 'p-invalid': passwordConfirmationError }"
                        >
                        </Password>
                        <label for="password">Confirm Password</label>
                      </span>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="w-100 mt-4">
                      <Button
                        label="Sign up"
                        class="w-100"
                        type="submit"
                        style="background-color: #79589f"
                      />
                    </div>
                  </div>
                </div>
                <!-- </div> -->
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

<script>
import User from "../../../apis/User";
export default {
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      nameError: null,
      passwordError: null,
      passwordConfirmationError: null,
      emailError: null,
    };
  },

  computed: {
    isLogin() {
      return this.$store.state.isLogin;
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
    submitRegisterForm() {
      this.nameError = null;
      this.passwordError = null;
      this.passwordConfirmationError = null;
      this.emailError = null;

      if (!this.form.name) {
        this.nameError = "Email is required";
      }
      if (!this.form.email) {
        this.emailError = "Email is required";
      }
      if (!this.form.password) {
        this.passwordError = "Password is required";
      }
      if (this.form.password != this.form.password_confirmation) {
        this.passwordConfirmationError = "Password is required";
        this.$toast.add({
          severity: "error",
          summary: "Error Message",
          detail: "password & password confirmation does not match",
          life: 6000,
        });
      }
      if (
        !this.passwordError &&
        !this.passwordConfirmationError &&
        !this.nameError &&
        !this.emailError
      ) {
         this.$store.dispatch("displaySpinnerPage",false);
        User.register(this.form)
          .then(() => {
            this.$router.push({ path: "/user/login" });
          })
          .catch((error) => {
            if (error.response.status == 422) {
              let errors = error.response.data.errors;
              if (errors.email) {
                errors.email.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Error Message",
                    detail: element,
                    life: 6000,
                  });
                });
              }
              if (errors.name) {
                errors.name.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Error Message",
                    detail: element,
                    life: 6000,
                  });
                });
              }
              if (errors.password) {
                errors.password.forEach((element) => {
                  this.$toast.add({
                    severity: "error",
                    summary: "Error Message",
                    detail: element,
                    life: 6000,
                  });
                });
              }
            }
          })
          .finally(() => {
         this.$store.dispatch("displaySpinnerPage",true);
          });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.form-container {
  margin-top: 6em;
}

::v-deep(.p-password input) {
  width: 100%;
  border-color: #79589f ;
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
.p-inputtext:hover{
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

.b-example-divider {
  height: 3rem;
  background-color: rgba(0, 0, 0, 0.1);
  border: solid rgba(0, 0, 0, 0.15);
  border-width: 1px 0;
  box-shadow: inset 0 0.5em 1.5em rgba(0, 0, 0, 0.1),
    inset 0 0.125em 0.5em rgba(0, 0, 0, 0.15);
}

.b-example-vr {
  flex-shrink: 0;
  width: 1.5rem;
  height: 100vh;
}

.bi {
  vertical-align: -0.125em;
  fill: currentColor;
}

.nav-scroller {
  position: relative;
  z-index: 2;
  height: 2.75rem;
  overflow-y: hidden;
}

.nav-scroller .nav {
  display: flex;
  flex-wrap: nowrap;
  padding-bottom: 1rem;
  margin-top: -1px;
  overflow-x: auto;
  text-align: center;
  white-space: nowrap;
  -webkit-overflow-scrolling: touch;
}
</style>