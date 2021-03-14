

class DataService
{
    database="";
    collection="";
    client=null;

    constructor(database,collection, client) {
        this.collection=collection;
        this.database=database;
        this.client=client;
    }

    find=async (query,skip,limit,sort)=>
    {
       let url=`/api/data/${this.database}/${this.collection}`;
       
       let response= await this.client.get(url,
        {
            "query": JSON.stringify(query),
            "skip":skip,
            "limit":limit,
            "sort": JSON.stringify(sort)
        });

        if(response.status!=200) return [];
        let payload=response.data;
        return payload;
    }    


    get=async (id)=>
    {
       let url=`/api/data/${this.database}/${this.collection}/${id}`;
       
       let response= await this.client.get(url,{});

        if(response.status!=200) return [];
        let payload=response.data;
        return payload;
    }   
    
    save=async (element)=>
    {
        let url=`/api/data/${this.database}/${this.collection}/${element._id}`;

        let method="POST";
        if(element._id)
        {
            method="PUT";          
        }

        let result=await this.client.request({
            "method": method,
            url: url,
            data: element
          });

        return result;


    }
}


class DataServiceFactory
{

    app=null;

    constructor(app)
    {
        this.app=app;
    }

    get= async (database,collection)=>
    {
        console.log(this.app.client);
        return new DataService(database,collection,this.app.client);
    }
}

export default DataServiceFactory;
