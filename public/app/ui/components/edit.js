import ApiFarm from '/app/core/apiFarm.js'




  export default  ApiFarm.extend("edit",{
    data(){
        return {
            monacoOptions: {
                language: 'json',
                scrollBeyondLastLine: false,                
              },
            item:{},
            itemJson:"{'prova':'prova'}",
            isValid:true

        };
    },
    props:
      {
        'db':{
          type: String,
          default()
          { 
            return this.$route.params.db;
          }
        }, 
        'collection':{
          type: String ,
          default()
          { 
            return this.$route.params.collection;
          }
        }, 
        'id':{
          type: String ,
          default()
          { 
            return this.$route.params.id;
          }
        }
      }
    ,
    
    methods:
    {
      amdRequire: require,         
      onChange: function() 
      {
        console.log("changed");
        console.log(this.item);
        try
        {
          this.item=JSON.parse(this.itemJson);
          this.isValid=true;
        }
        catch
        {
          this.isValid=false;
        }
      },
      onSave: async function() 
      {
        console.log("saving");       
        
        let data= await this.dataService.save(this.item);

        if(data!=null)
        {
          this.emit("ui.message",{
            "message":"The item was saved",
            "type":"success"
          });
        }
      },
      resizeMonaco: function() {
        const monacoEditor = this.$refs.monaco.getMonaco();
        const oldLayout = monacoEditor.getLayoutInfo();    
        monacoEditor.layout({ width: this.$refs.monacoparent.clientWidth, height: this.$refs.monacoparent.clientHeight });
      },
    },
    mounted: async function () {
      this.dataService=await this.services.dataServiceFactory.get(this.db, this.collection);

      let data= await this.dataService.get(this.id);
      this.item=data.data;
      this.itemJson=JSON.stringify(this.item, null, 2);
    }
  });