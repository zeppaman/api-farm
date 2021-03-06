

class ApiFarm
{
  services={};
  components={};
  modules={};
  routes=[];
  app={};
  router={};
  config={};
  menu=[];

   emit= function(event,data)
   {
    apiFarm.app._events[event][0](data);
   };
    extend= function extend(name,component)
    {
        return Vue.component(name, (resolve, reject) => {

          if(component.methods==undefined)
          {
            component.methods={};
          }         
          component.methods.emit=function(eventname, data)
          {
            this.$root.$emit(eventname, data);
          };

          component.methods.subscribe=function(eventname, manager)
          {
            this.$root.$on(eventname, manager);
          };

          if(component.template===undefined)
          {
              console.log("resolve template");
                console.log(this.components);
                let basePath=this.components[name].module.modulePath;
                component.template= `${basePath}/template/${name}.vue`
          }
          var that=this;
            axios
            .get(component.template)
            .then(response => {        
              
                component.template=response.data; 
                console.log(name);
                console.log(component.props); 
                if(component.props==undefined)
                {
                  component.props={};
                }             
                component.props.services={
                  type: Object,
                  default()
                  { 
                    return that.services;
                  }
                };

                console.log(component)              ;
                resolve(component);
            });
        });
    };  

    createClient= async() =>
    {
      let client=axios.create();

      client.interceptors.request.use(function (config) {
        const token = localStorage.getItem("token");
        config.headers.Authorization =  token;                        
        return config;
    });
    
    
     client.interceptors.response.use(
        response => response,
        error => 
        {      
          if (error.response.status===401) {
            localStorage.removeItem("token");
            if( document.location.pathname !=  window.apiFarm.config.loginUrl)
            {
              document.location.href =  window.apiFarm.config.loginUrl;
            }
          } 
          else 
          {
            window.apiFarm.emit("ui.message",
            { 
              type:"error",
              message:"An error occurred"
            });
            console.error(response);
            Promise.reject(error);
          }
        }
      );
      return client;
    };

    loadModules =async() =>
    {

    
      for (const [key, value] of Object.entries(this.config.modules)) 
        {
          console.log("module found "+key+" "+value);
          let module=await import(value);
          console.log(module.default);
          this.modules[key]=module.default;
        }
    };

    initModules= async ()=>
    {
      for (const [key, value] of Object.entries(this.modules))
      {
        console.log("initing module"+key);
       
        if (typeof value.init !== "undefined") {
          await value.init(this);
        }
      }
      console.log("registering init END");

    };

    registerServices= async ()=>
    {
      for (const [key, value] of Object.entries(this.modules) )
      {
        console.log("registering services for"+key);
        if (typeof value.registerServices !== "undefined") {
          await value.registerServices(this.services,this);
        }        
      };

      console.log(this.services);
      console.log("registering services END");
    };

    registerComponents= async ()=>
    {
      for (const [key, value] of Object.entries(this.modules) )
        {
          console.log("registering components for"+key);
          if (typeof value.registerComponents !== "undefined") {
           let result= await value.registerComponents(this.components,this);           
          }
        }; 

        console.log("registering components END");
    };


    registerRoutes= async ()=>
    {
      for (const [key, value] of Object.entries(this.modules)) 
      {
          console.log("registering routing for"+key);
          if (typeof value.registerRoutes !== "undefined") {
            await value.registerRoutes(this.routes,this);
          }
        
      };      
    };


    registerMenus= async ()=>
    {
      console.log("registering menus");
      let idx=1;
      for (const [key, value] of Object.entries(this.modules)) 
      {
          console.log("registering menus for"+key);
          if (typeof value.registerMenus !== "undefined") {
            await value.registerMenus(this.menu,this, idx);
            idx+=100;
          }
        
      };      
      console.log(this.menu);
    };

    

    load= async ()=>
    {     
       
      let config= await axios.get("/config.json");
      this.config=config.data;

      this.client= await this.createClient();

       console.log(this.modules);

       await this.loadModules();

       await this.initModules();

       await this.registerServices();

       await this.registerComponents();

       await this.registerRoutes();

      
       

        var router = new VueRouter({
          mode: 'history',
          routes: this.routes,
          base: '/bundles/core'
        });

        router.beforeEach((to, from, next) => {
          next();
        if (to.matched.some(record => record.meta.requiresAuth)) {
          // this route requires auth, check if logged in
          // if not, redirect to login page.
          if (!auth.loggedIn()) {
            next({
              path: '/app/login',
              query: { redirect: to.fullPath }
            })
          } else {
            next()
          }
        } else {
          next() // make sure to always call next()!
        }
      });
    

    console.log(router);
    this.router=router;
    let appcomponent=  this.components.app.component;



     this.app= new Vue({
          el: '#app', 
          router: router,
          render(h) {
            return h( appcomponent);
          },
          vuetify: new Vuetify(),
          });
      
       this.isLoggedIn=localStorage.getItem("token")!=null;       

        return this;

    };

    loadComponents= async (module,components)=>
    {
      let result={};
      let promises=[]

      await Promise.all(components.map(async (element) => {
        let path=`${module.modulePath}/components/${element}.js`;      
        let imported= await import(path);  
        result[element]={
          "component": imported.default,
          "module": module
        };
      }));    
      
      return result;
    };
    
    navigate= (name, params)=>
    {
      this.router.push({
        "name":name,
        "params":params
      });
    };

    login= async (username,password)=>
    {
       
        console.log(this.config);

        let params = new URLSearchParams()
        params.append('username', username);
        params.append('password', password);
        params.append('client_id', this.config.oauthData.client_id);
        params.append('client_secret', this.config.oauthData.client_secret);
        params.append('grant_type', this.config.oauthData.grant_type);

        let url= this.config.tokenBaseUrl+this.config.tokenEndpoint;
        const config = {
            headers: { 'content-type': 'application/x-www-form-urlencoded' },
          };

        this.client.post(url, params, config).then(response => {
          if(response.status=="200")
          {
              localStorage.setItem("token",response.data.token_type+" "+ response.data.access_token);
             document.location.href=this.config.afterLoginUrl;
              this.isLoggedIn=true;
              
          }
          }); 


    }
}

let apiFarm= new ApiFarm();




export default apiFarm;