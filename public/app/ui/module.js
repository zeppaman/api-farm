

class UiModule 
{
    moduleName= "ui";
    modulePath= "/app/ui/";
    init= async(app) =>
    {
        console.log("init");
    };
    registerServices= async(services,app) =>
    {
        console.log("registering services");
        
    };
    registerComponents= async(components,app) =>
    {        
        console.log("loading components for "+this.moduleName);
        let loaded=await app.loadComponents(this,['grid', 'edit', 'login', 'sidebar','welcome','app']);
        for (const [key, value] of Object.entries(loaded) )
        {
          components[key]=value;
        }
        return loaded;
    };
    registerRoutes= async(routes,app) =>
    {
      console.log(app);
        console.log("registering routes for "+this.moduleName);
        console.log(routes);
        Array.prototype.push.apply(routes,[
            {
              path: '/app/login',
              name: 'login',
              component:app.components.login.component, // async (res, rej) => import('./components/login.js'),
              meta: {
                requiresAuth: false,
              },
            },
            {
              path: '/app/data/:db/:collection',
              name: 'grid',
              component:app.components.grid.component, 
              props: {
                default: true,
                // db: route => ({ search: route.query.q }),
                // collection: route => ({ search: route.query.q })
                
              }
            },
            {
              path: '/app/data/:db/:collection/:id',
              name: 'edit',
              component:app.components.edit.component, 
              props: {
                default: true,
                // db: route => ({ search: route.query.q }),
                // collection: route => ({ search: route.query.q })
                
              }
            },
            {
              path: '/app/welcome',
              name: 'welcome',
              component:app.components.welcome.component, 
              props: {
                default: true,
                // db: route => ({ search: route.query.q }),
                // collection: route => ({ search: route.query.q })
                
              }
            },
          ]);

          console.log(routes);
    };
};

export default new UiModule();
