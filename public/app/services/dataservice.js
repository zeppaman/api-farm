

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

        let payload=response.data;
        return payload;
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
