// import Vuex, { mapActions } from "vuex";
import { createStore } from "vuex";
const store = createStore({
  state: {
    userData: JSON.parse(sessionStorage.getItem("User")),
    NUR: null,
    displaySpinnerPage: true,
    siteAlarms: null,
    displayDialog: false,
    dialogMessage: "",
  },
  getters: {
    isLogin(state) {
      return state.userData;
    },
    token(state) {
     
      if (state.userData) {
        let userToken = state.userData.token.plainTextToken;
        return userToken;
      } else {
        let userToken = null;

        return userToken;
      }
    },
    isAdmin(state) {
     
      if (state.userData) {
        let roles = state.userData.roles;
        return roles;
      } else {
        let roles = null;

        return roles;
      }
    },
    userName(state) {
    
    if (state.userData) {
        let userName = state.userData.user.name;
        return userName;
      } else {
        let userName = null;
        return userName;
      }
    },
  },
  mutations: {
    NEW_STATUS(state, status) {
      state.isLogin = status;
    },
    USER_DATA(state, user) {
      state.userData = user;
    },
    USER_PERMISSIONS(state, permissions) {
      state.userPermissions = permissions;
    },
    USER_Roles(state, Roles) {
      state.userRoles = Roles;
    },
    CHANGE_TIME_OUT(state, status) {
      state.sessionTimeOut = status;
    },
    SET_NUR(state, nur) {
      state.NUR = nur;
    },
    DISPLAY_SPINNER(state, status) {
      state.displaySpinnerPage = status;
    },
    SITE_ALARMS(state, alarms) {
      state.siteAlarms = alarms;
    },
    DISPLAY_DIALOG(state, status) {
      state.displayDialog = status;
    },
    DIALOG_MESSAGE(state, message) {
      state.dialogMessage = message;
    },
  },
  actions: {
    changeLoginState({ commit }, status) {
      commit("NEW_STATUS", status);
    },
    userData({ commit }, user) {
      commit("USER_DATA", user);
    },
    userPermissions({ commit }, permissions) {
      commit("USER_PERMISSIONS", permissions);
    },
    userRoles({ commit }, roles) {
      commit("USER_Roles", roles);
    },
    setNUR({ commit }, NUR) {
      commit("SET_NUR", NUR);
    },
    displaySpinnerPage({ commit }, status) {
      commit("DISPLAY_SPINNER", status);
    },
    siteAlarms({ commit }, alarms) {
      commit("SITE_ALARMS", alarms);
    },
    displayDialog({ commit }, status) {
      commit("DISPLAY_DIALOG", status);
    },
    dialogMessage({ commit }, message) {
      commit("DIALOG_MESSAGE", message);
    },
  },
});
export default store;
