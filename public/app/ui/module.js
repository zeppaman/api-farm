

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
  
        console.log("registering routes for "+this.moduleName);
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

    registerMenus= async(menus,app, idx)=>
    {     
      console.log("MENU LOADSING");
      let service=await app.services.dataServiceFactory.get("test","_schema");
      let query={
        
      }
      let items=await service.find(query,0,100,{});

      
      let dbmenu={
        id: idx++,
        name: 'Data :',
        children: [
        ],
      };

      let dbs= [...new Set(items.data.map((x)=>x.db))];
      
      dbs.forEach(x=>{
        // add db items
        let dbnode={id: idx++, name: x, children:[]};
        //add child menu items
        
        items.data.filter(x=>x.db="test").forEach((x) =>
        {
          dbnode.children.push(
            { 
              id: idx++, 
              name: x.name,
              route: {
                name: 'grid',
                params:
                {
                  db:x.db,
                  collection:x.name
                }
              }
            }
          );
          idx++;
        });

        dbmenu.children.push(dbnode);
      });

      console.log(dbmenu);

      menus.push(dbmenu);
    }
};

export default new UiModule();
