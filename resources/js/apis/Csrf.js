import allInstances from "./Api";

export default {

    getCookie(){
       return allInstances.Api.get('/csrf-cookie')
            
        
    }
}