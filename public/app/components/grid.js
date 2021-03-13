import ApiFarm from '/app/core/apiFarm.js'




  export default  ApiFarm.extend("grid",{
    data: () => ({
      entityName: '',
      items: [],
      headers:[],
      skip:0,
      limit:20,
      itemsPerPage:20,
      pageIndex:1,
      sorting:{},
      itemsCount:100,
      isLoading:true,
      dataService:{},
      schemaService:{},
      
    }),
    props: {
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
    }
  },
    methods: {
      setSchema : async function() {
       
        let query={
          'db':this.db,
          'collection':this.collection,
        };
        
        let schema= await this.schemaService.find(query,0,1,{});
        let objects= schema.data[0].fields;

        for (const field in objects) {
            let element=objects[field];
            console.log(element);
            this.headers.push( {
                text: element.label,               
                sortable: true,
                value: element.name,
              });
        }       
      },
      fetchdata : async function() {
       
        this.isLoading = true;
        let serversorting={};
        if(this.sorting.sortBy)
        {
          let sortdesc=1;
          if(this.sorting.sortBy)
          {
            sortdesc=-1;
          }
          serversorting[this.sorting.sortBy]= sortdesc;
        }
        let data=await this.dataService.find({}, this.skip,this.limit,serversorting);
        return {
             items:data.data,
             itemsCount:1022
        };
      },
      goTo: function(item) {
        console.log(item);
      },   
      paginate: function(pagination)
      {
          this.pageIndex=pagination.page;
          this.skip=(pagination.page-1)*pagination.itemsPerPage;
          this.limit=pagination.itemsPerPage;
          
      },
      sortBy: function(sort)
      {
          console.log(sort);
          this.sorting.sortBy=sort;
      },
      sortDesc: function(sort)
      {
          console.log(sort);
          this.sorting.sortDesc=sort;
      }
    },   

    mounted: async function () {
   
      this.dataService=await this.services.dataServiceFactory.get(this.db, this.collection);
      this.schemaService=await this.services.dataServiceFactory.get("test", "_schema");
      console.log(this.dataService);
      await this.setSchema();

      this.fetchdata().then(x=> {
        console.log("fetched");
          this.items=x.items;
          this.itemsCount=x.itemsCount;
          this.isLoading=false;
        });
  },
    template:"/app/template/grid/grid.vue"
  });