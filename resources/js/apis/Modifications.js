import allInstances from "./Api";

export default {

    getModificationAnalysis()
    {
        return allInstances.Api.get("/modifications/analysis");
    },
    getModificationIndex(data)
    {
        return allInstances.Api.get(`/modifications/index/${data.columnName}/${data.columnValue}`);
    },
    getSiteModifications(siteCode)
    {
        return allInstances.Api.get(`/modifications/siteModifications/${siteCode}`);

    },
    insertNewModification(modification)
    {
        return allInstances.Api.post("/modifications/new",modification)
    },
    getModificationDetails(id)
    {
        return allInstances.Api.get(`/modifications/details/${id}`)

    },
    updateModification(data)
    {
        return allInstances.Api.post("/modifications/update",data);

    },
    deleteModification(data)
    {
        return allInstances.Api.post("/modifications/delete",data);

    },
    downloadModifications(data)
    {
        return allInstances.downloadApi.post("/modifications/download",data);

    }
    
};