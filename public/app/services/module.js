import DataServiceFactory from  '/app/services/dataservice.js'
console.log(DataServiceFactory);

export default {
    moduleName: "services",
    modulePath: "/app/services/",
    init: async(app) =>
    {
        console.log("init");
    },
    registerServices: async(services,app) =>
    {
        console.log("registering services");
        services.dataServiceFactory=new DataServiceFactory(app);
    },
    registerRoutes: async(components,app) =>
    {

    },
    registerRoutes: async(routes,app) =>
    {

    }
};

