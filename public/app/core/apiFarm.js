import dataModule from '/app/core/dataservice.js'


class ApiFarm
{
  services={};

    extend= function extend(name,component)
    {
        return Vue.component(name, (resolve, reject) => {
          var that=this;
            axios
            .get(component.template)
            .then(response => {        
                component.template=response.data; 
                console.log(name);
                console.log(component.props);              
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
            window.router.push({ name: 'login', params: { return: document.location.href } });
          } else {
            Promise.reject(error);
          }
        }
      );
      return client;
    };
    load= async ()=>
    {
      this.client=await this.createClient();
        
        await axios.get("/app/config.json").then(x=>
        {
                console.log("LOADED");
                if(x.data)
                { 
                   
                  localStorage.setItem("ApiFarm:config",x.data);
                  this.config=x.data;
                }                
          });
        
        dataModule.init(this);

        dataModule.registerServices(this.services,this);

        return this;

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
                window.router.push({ name: 'grid', params: { return: document.location.href } });
            }
          }); 


    }
}

let apiFarm= new ApiFarm();




export default apiFarm;