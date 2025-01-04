<template>
  <div class="container">
    <div class="reset-password">
      <div class="alert alert-primary">
        <form @submit.prevent="sendToken">
          <div class="form-group">
            <label for="email">Email</label>
            <input
              type="email"
              name="email"
              v-bind:class="{ 'is-invalid': errorEmail, 'is-valid': infoEmail }"
              class="form-control"
              placeholder="Email...."
              v-model="emailForm.email"
              id="email"
            />
            <div class="invalid-feedback">
              {{ errorEmail }}
            </div>
            <div class="valid-feedback">
              {{ infoEmail }}
            </div>
          </div>
          <button class="btn btn-primary mt-2" type="submit">
            Send Token To Email Address
          </button>
        </form>
      </div>

      <div class="alert alert-secondary">
        <form @submit.prevent="validateToken">
          <div class="form-group">
            <label for="token">Token</label>
            <input
              type="text"
              name="token"
              placeholder="Token...."
              v-bind:class="{ 'is-invalid': errorToken, 'is-valid': infoToken }"
              class="form-control"
              id="token"
              v-model="tokenForm.token"
            />
            <div class="invalid-feedback">
              {{ errorToken }}
            </div>
            <div class="valid-feedback">
              {{ infoToken }}
            </div>
          </div>
          <button class="btn btn-secondary mt-2" type="submit">
            Validate Token
          </button>
        </form>
      </div>

      <div class="alert alert-success" v-if="tokenValid">
        <form @submit.prevent="changePassword">
          <div class="form-group">
            <label for="newpassword">Change Password</label>
            <input
              type="password"
              class="form-control"
              v-bind:class="{ 'is-invaled': errorNewPassword }"
              id="newpassword"
              v-model="newPassword"
              autocomplete="off"
            />
            <div >
              {{ errorNewPassword }}
            </div>
            <div v-if="passwordBackendError">
                <p v-for="error in passwordBackendError" :key="error">{{error}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="password-confirmation">Password Confirmation</label>
            <input
              type="password"
              class="form-control"
              v-bind:class="{ 'is-invaled': errorPasswordConfirmation }"
              id="password-confirmation"
              v-model="passwordConfirmation"
              autocomplete="off"
            />
            <div >
              {{ errorPasswordConfirmation }}
            </div>
          </div>
          <button type="submit" class="btn btn-success mt-2">
            Change Password
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import User from "../../../apis/User.js";
export default {
  data() {
    return {
      emailForm: {
        email: "",
      },
      tokenForm: {
        token: "",
      },

      newPassword: "",
      passwordConfirmation: "",

      errorNewPassword: null,
      errorPasswordConfirmation: null,

      errorEmail: null,
      infoEmail: null,
      infoToken: null,
      user_id: null,
      passwordBackendError:null,

      errorToken: null,

      tokenValid: false,
    };
  },
  name: "resetPassword",
  methods: {
    sendToken() {
      this.errorEmail = null;
      this.infoEmail = null;
      if (!this.emailForm.email) {
        this.errorEmail = "Email Field is Required";
      }
      if (!this.errorEmail) {
        User.sendToken(this.emailForm)
          .then(() => {
            this.infoEmail = "Email Sent, please check your mail";
          })
          .catch((error) => {
            if (error.response.status == 422) {
              this.errorEmail = error.response.data.errors.email;
            } else if (error.response.status == 401) {
              this.errorEmail = error.response.data.error;
            }
          });
      }
    },
    validateToken() {
      this.errorToken = null;
      if (!this.tokenForm.token) {
        this.errorToken = "Token is required";
      } else {
        User.validateToken(this.tokenForm)
          .then((response) => {
            if (response.data.id) {
              this.infoToken = "Token is Valid";
              this.user_id = response.data.id;
              this.tokenValid = true;
            }
          })
          .catch((error) => {
            if (error.response.status == 422) {
              this.errorToken = error.response.data.errors.token;
            } else if (error.response.status == 401) {
              this.errorToken = error.response.data.error;
            }
          });
      }
    },
    changePassword() {
      this.errorPasswordConfirmation = null;
      this.errorNewPassword = null;
      this.passwordBackendError=null;
      if (!this.newPassword) {
        this.errorNewPassword = "New Password is required";
      }
      if (!this.passwordConfirmation) {
        this.errorPasswordConfirmation = "Password confirmation is required";
      }
      if (this.passwordConfirmation != this.newPassword) {
        this.errorPasswordConfirmation = "Password are not matched";
      }
      if (!this.errorPasswordConfirmation && !this.errorNewPassword) {
        const data = {
          user_id: this.user_id,
          password: this.newPassword,
          password_confirmation: this.passwordConfirmation,
        };
        User.resetPassword(data)
          .then(() => {
            this.$router.push("/user/login");
          })
          .catch(error=>{
            if(error.response.status==422)
            {
              
                this.passwordBackendError=error.response.data.errors.password;
                
            }
          });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.reset-password {
  margin-top: 6em;
  width: 70%;
  margin-left: auto;
  margin-right: auto;
}
</style>