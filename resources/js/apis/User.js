import allInstances from "./Api";
import Csrf from "./Csrf";
export default {
    async register(form) {
      await Csrf.getCookie();
      return  allInstances.Api.post("/user/register", form);
        
    },
   async login(form) {
        await Csrf.getCookie();
        return allInstances.Api.post("/user/login", form);
    },
    async logout() {
        await Csrf.getCookie();
        return allInstances.Api.post("/user/logout");
    },
    async resetPassword(form) {
        await Csrf.getCookie();
        return allInstances.Api.post("/user/resetPassword", form);
    },
    async validateToken(form) {
        await Csrf.getCookie();
        return allInstances.Api.post("/user/validateToken", form);
    },
    async sendToken(form) {
        await Csrf.getCookie();
        return allInstances.Api.post("/user/sendToken", form);
    },
    refreshSession(){

        return allInstances.Api.get("/user/refreshsession");

    }
};
