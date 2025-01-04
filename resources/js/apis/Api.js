import axios from "axios";
import { defaults } from "lodash";
import router from "../router/index";
import store from "../vuex/store";

let Api = axios.create({
    baseURL: "/api",
    headers: {
        "Content-Type": "application/json",
       
        
    },
    
});

Api.defaults.withCredentials = true;
Api.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        if (
            error.response.status == 403 ||
            error.response.status == 419 ||
            error.response.status == 401
        ) {
            sessionStorage.removeItem("Auth");
            sessionStorage.removeItem("userData");
            store.dispatch("changeLoginState", false);
            store.dispatch("userData", null);
            store.dispatch("userPermissions", null);
            store.dispatch("userRoles", null);
            router.push({ path: "/user/login" });
        }

        return Promise.reject(error);
    }
);

let downloadApi = axios.create({
    baseURL: "/api",

    responseType: "blob",
    headers: {
        "Content-Type": "application/json",
    },
   
});
let uploadApi=axios.create({
    baseURL: "/api",
    headers: {
        "Content-Type": "multipart/form-data",
    },

});
uploadApi.defaults.withCredentials=true;
uploadApi.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        if (
            error.response.status == 403 ||
            error.response.status == 419 ||
            error.response.status == 401
        ) {
            sessionStorage.removeItem("Auth");
            sessionStorage.removeItem("userData");
            store.dispatch("changeLoginState", false);
            store.dispatch("userData", null);
            store.dispatch("userPermissions", null);
            store.dispatch("userRoles", null);
            router.push({ path: "/user/login" });
        }

        return Promise.reject(error);
    }

);
downloadApi.defaults.withCredentials = true;
downloadApi.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        if (
            error.response.status == 403 ||
            error.response.status == 419 ||
            error.response.status == 401
        ) {
            sessionStorage.removeItem("Auth");
            sessionStorage.removeItem("userData");
            store.dispatch("changeLoginState", false);
            store.dispatch("userData", null);
            store.dispatch("userPermissions", null);
            store.dispatch("userRoles", null);
            router.push({ path: "/user/login" });
        }

        return Promise.reject(error);
    }
);

function allInstances() {
    return {
        Api: Api,
        downloadApi: downloadApi,
        uploadApi:uploadApi,
    };
}

export default allInstances();
