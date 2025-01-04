<template>
  <nav class="navbar mb-5 navbar-expand-lg">
    <a class="navbar-brand font-weight-bolder">CairoSouth</a>
    <div class="form-group">
      <form class="form-inline ml-5" @submit.prevent="submitSearch">
        <div class="p-inputgroup">
          <InputText v-model="search" placeholder="Site Code/Name......." />
          <Button icon="pi pi-search" type="submit" class="p-button-warning" />
        </div>
      </form>
    </div>

    <a
      class="ofcan-btn"
      data-bs-toggle="offcanvas"
      data-bs-target="#offcanvasExample"
      aria-controls="offcanvasExample"
    >
      <div class="menue-btn">
        <div class="menue-btn-burger"></div>
      </div>
    </a>
    <div
      class="collapse flex-grow-0 navbar-collapse"
      id="navbarSupportedContent"
    >
      <ul class="navbar-nav">
        <li class="nav-item active">
          <router-link class="nav-link" to="/home" Active>Home</router-link>
        </li>
        <li class="nav-link" v-if="isLogin">
          {{ userName }}
        </li>

        <li class="nav-item">
          <p class="nav-link"></p>
        </li>
        <li class="nav-item">
          <router-link to="/dashboard" class="nav-link" v-if="isLogin"
            >Dashboard</router-link
          >
        </li>
        <li class="nav-item">
          <router-link v-if="!isLogin" class="nav-link" to="/user/login"
            >Login</router-link
          >
        </li>

        <li class="nav-item">
          <a class="nav-link" v-if="isLogin" @click="logout">Logout</a>
        </li>
        <li class="nav-item">
          <router-link v-if="!isLogin" class="nav-link" to="/user/register"
            >Register</router-link
          >
        </li>
      </ul>
    </div>
  </nav>

  <Toast />
</template>

<script>
import User from "../apis/User";
import Sites from "../apis/Sites";
import allInstances from "../apis/Api";

export default {
  data() {
    return {
      search: null,
    };
  },
  emits: ["displaySitesTable"],
  name: "navbar",

  computed: {
    isLogin() {
     
      return this.$store.getters.isLogin

    },
    token() {
      return this.$store.getters.token;
    },

    userName() {
      return this.$store.getters.userName;
    },
  },
  mounted() {
 
  },
  methods: {
    submitSearch() {
      this.$store.dispatch("displaySpinnerPage", false);
      // Sites.searchSites(this.search)
      allInstance.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
      allInstance.Api.get(`/sites/search/${this.search}`)
        .then((response) => {
          console.log(response);
          if (response.data.message == "No data Found") {
            this.$toast.add({
              severity: "info",
              summary: "Sorry!!!",
              detail: "No data Found",
              life: 3000,
            });
          } else {
            this.$emit("displaySitesTable", response.data.sites);
          }
        })
        .catch((error) => {
          console.log(error);
          if (error.response.status == 422) {
            this.$toast.add({
              severity: "error",
              summary: "Opes!!!",
              detail: error.response.data.errors.search[0],
              life: 3000,
            });
          }
        })
        .finally(() => {
         
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },

    logout() {
      this.$store.dispatch("displaySpinnerPage", false);

   
      allInstances.Api.defaults.headers[
        "Authorization"
      ] = `Bearer ${this.token}`;
      allInstances.Api.post("user/logout")
        .then((data) => {
          console.log(data);
           this.$store.dispatch("userData", null);
          sessionStorage.removeItem("User");
         

          // sessionStorage.removeItem("Auth");
          // sessionStorage.removeItem("userData");
          // this.$store.dispatch("changeLoginState", false);
          // this.$store.dispatch("userData", null);
          // this.$store.dispatch("userPermissions", null);
          // this.$store.dispatch("userRoles", null);
          // this.isLogin=false;
          this.$router.push({ path: "/user/login" });
        })
        .catch((error) => {
          // sessionStorage.removeItem("User");
          // this.$store.dispatch("userData");
        })
        .finally(() => {
          this.$store.dispatch("displaySpinnerPage", true);
        });
    },
  },
};
</script>

<style lang="scss"  scoped>
.navbar {
  position: fixed;
  display: flex;
  background-color: var(--purple-500);
  justify-content: space-around;
  z-index: 100;
  width: 100%;
  top: 0;
  left: 0;
  .nav-link,
  .navbar-brand {
    color: white;
    cursor: pointer;
  }
  .navbar-brand {
    font-size: 1.5rem;
    font-weight: 800;
  }
  .ofcan-btn {
    display: none;
  }

  .menue-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.5s ease-in-out;
  }
  .menue-btn-burger {
    position: relative;
    width: 25px;
    height: 4px;
    background-color: white;
    border-radius: 3px;
    transition: all 0.5s ease-in-out;
  }
  .menue-btn-burger::before,
  .menue-btn-burger::after {
    position: absolute;
    content: "";
    top: 0;
    left: 0;
    width: 25px;
    height: 4px;
    background-color: white;
    border-radius: 3px;
    transition: all 0.5s ease-in-out;
  }
  .menue-btn-burger::before {
    transform: translateY(-10px);
  }
  .menue-btn-burger::after {
    transform: translateY(10px);
  }
  .menue-btn.open .menue-btn-burger {
    transform: translateX(-30px);
    background-color: transparent;
  }
  .menue-btn.open .menue-btn-burger::before {
    transform: rotate(45deg) translate(21px, -21px);
  }
  .menue-btn.open .menue-btn-burger::after {
    transform: rotate(-45deg) translate(21px, 21px);
  }

  .form-group {
    background-color: transparent;
    width: 40%;
    position: relative;
    // display: flex;
    // justify-content: space-between;
    // align-items: center;
    .search {
      width: 100%;
      // height: 30px;
    }
    .searchBtn {
      position: absolute;
      width: 15%;
      margin: 0 !important;
      top: 0;
      right: 0;
      // height: 28px;
      text-align: center;
      background-color: green;
      // border: 3px solid transparent;
    }
  }

  .collapse {
    display: flex;
    justify-content: center;
    .image_container {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: burlywood;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1rem;
      i {
        display: block;
      }
      img {
        display: block;
      }
    }
    .dropdown-item {
      cursor: pointer;
    }
    .dropdown:hover .dropdown-menu {
      display: block;
    }
    .test {
      position: relative;
      height: 30px;
      width: 100%;
      .test2 {
        background-color: #aaaaaa;
        position: absolute;
        top: 20%;
        right: 100%;
        display: none;
        border-radius: 3px;
        z-index: 1;
      }
    }
    .test:hover .test2 {
      display: block;
    }
  }
}
.p-button-warning {
  color: white !important;
}
.p-inputtext:focus {
  box-shadow: none !important;
  border: none !important;
}
.p-inputtext:hover {
  border: none !important;
}

.offcanvas {
  width: 20% !important;
  height: unset !important;
  .offcanvas-body {
    font-size: 1rem;
    background: #040b14;
    p {
      font-family: "Poppins", sans-serif;
      font-size: 1.1rem;
      font-weight: 300;
      line-height: 1.7em;
      color: #999;
    }
    .side-bar {
      padding-top: 2em;
      color: #fff;
      width: 100%;
      // height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      .image_container {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: burlywood;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1rem;
        i {
          display: block;
        }
        img {
          display: block;
        }
      }
      .components {
        border-bottom: 1px solid #47748b;
      }
      .components,
      .component {
        padding: 20px 0;
        width: 100%;
        p {
          color: #fff;
          padding: 10px;
          text-align: center;
        }
        .nav-link {
          color: white;
        }
        .nav-item {
          text-align: center;
        }

        .dropdown-item {
          cursor: pointer;
          text-decoration: none;
          text-align: center;
          padding: 10px;
          font-size: 1.1em;
          display: block;
          color: black;
        }
        .dropdown-item:hover {
          color: #7386d5;
          background: #fff;
        }
        .dropdown {
          position: relative;
        }
        .dropdown-menu {
          position: absolute;
          top: 80%;
          left: 20%;
        }
        .dropdown:hover .dropdown-menu {
          display: block;
        }
        .test {
          position: relative;
          height: 30px;
          width: 100%;
          .test2 {
            background-color: #aaaaaa;
            position: absolute;
            top: 100%;
            left: 20%;
            z-index: 1;

            display: none;
            border-radius: 3px;
          }
        }
        .test:hover .test2 {
          display: block;
        }

        .test2:hover {
          display: block;
        }
      }
    }
  }
}

@media screen and (max-width: 575px) {
  .navbar {
    .navbar-brand {
      font-size: 1rem;
    }
    .ofcan-btn {
      display: inline-block;
    }
    .collapse {
      display: none;
    }
    .form-group {
      width: 45%;
      .searchBtn {
        width: 45%;

        font-size: 1rem;
        padding-left: 0;
        padding-right: 0;
      }
    }
  }
  .offcanvas {
    width: 55% !important;
    .offcanvas-body {
      p {
        font-size: 1rem;
      }
    }
  }
}

@media (min-width: 576px) and (max-width: 767px) {
  .navbar {
    .ofcan-btn {
      display: inline-block;
    }
    .collapse {
      display: none;
    }
    .form-group {
      width: 60%;
      .searchBtn {
        width: 30%;
      }
    }
  }
}

@media (min-width: 768px) and (max-width: 991px) {
  .navbar {
    .ofcan-btn {
      display: inline-block;
    }
    .collapse {
      display: none;
    }
    .form-group {
      width: 70%;
      .searchBtn {
        width: 30%;
      }
    }
  }
}

@media (min-width: 992px) and (max-width: 1199px) {
  .navbar {
    .collapse {
      justify-content: flex-end;
      width: 80%;
      .form-group {
        width: 50%;
      }
    }
  }
}

@media (min-width: 1200px) and (max-width: 1500px) /* extra large windows*/ {
}
</style>