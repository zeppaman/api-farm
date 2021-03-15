# API FARM
Api Farm is a low code platform based on Symfony and MongoDB. It support out of the box:

- Data management
- Rest API
- User management
- Oaut2 authentication (as standalone server, or JWT resource server)
- OpenID connect support
- Command line automation
- GrapQL with query and mutations
- Dynamic endpoint generation 


# Install
Just clone and run it
```bash

git clone https://github.com/zeppaman/API-Farm.git .
docker-compose up

bin/console assets:install public --symlink --relative
```


# API Usage

In this section we will see how to use API Farm.

## Console commands

```bash
bin/console app:crud:upsert db collection {\"filed\":\"value\"}
bin/console app:crud:find db collection {\"filed\":\"value\"}
bin/console app:crud:delete db collection {\"filed\":\"value\"}
```


curl --location --request POST 'http://localhost/api/data/test/_schemas' \
--header 'Content-Type: text/plain' \
--data-raw 


## Api REST 

It uses the resouce URI pattern. 

**Collection methods**
- GET  `http://localhost/api/{db}/{collection}` + GET for list,
- POST `http://localhost/api/{db}/{collection}`  list,

**item methods**
- GET `http://localhost/api/{db}/{collection}/{id}`  replace the object,
- PUT `http://localhost/api/{db}/{collection}/{id}`  replace the object,
- PATCH `http://localhost/api/{db}/{collection}/{id}`  pathc the object,
- DELETE `http://localhost/api/{db}/{collection}/{id}`  delete the object,

### find

http://localhost/api/data/test/prova?skip=10&limit=20&query={%22title%22:%22value%22}

```
{"request":{"database":"test","collection":"test","skip":"10","limit":"20","query":"{}"},"data":[{"_id":"604335dcddfd8c484b4da692","title":"mytitle","body":"mybody"},{"_id":"604335ea05dcae70fe1ed752","title":"mytitle","body":"mybody"},{"_id":"604335f88288e669541ccb72","title":"mytitle","body":"mybody"},{"_id":"6043363cf9265b45a810f262","title":"mytitle","body":"mybody"},{"_id":"60433646825e954b41534232","title":"mytitle","body":"mybody"},{"_id":"6043368e22619b6cd151d852","title":"mytitle","body":"mybody"},{"_id":"6043369ca48c2a285d4682f2","title":"mytitle","body":"mybody"},{"_id":"604336f26e205328b82ddd32","title":"mytitle","body":"mybody"},{"_id":"6043370630658259257fd982","title":"mytitle","body":"mybody"},{"_id":"60433722f915fc6ba00f5292","title":"mytitle","body":"mybody"},{"_id":"6043387ef42a7a265a743c02","filed":"value"}],"metadata":[]}
```

### GET

http://localhost/api/data/test/test/60428383bdb90439ec593df2

```
{"request":{"database":"test","collection":"test","id":"60428383bdb90439ec593df2"},"data":{"_id":"60428383bdb90439ec593df2","ddd":"ddd"},"metadata":[]}
```



# OAUTH2

## Generate a client

```bash
 bin/console trikoder:oauth2:create-client

 -------- 
  Identifier                         Secret                                                                                                                            
 ---------------------------------- ---------------------------------------------------------------------------------------------------------------------------------- 
  c0a71bf0379c66c46da3ed41a4f4aab2   e8f9855c30bb9915e61bc093656e75c7e8e3bc3b221eca2be796790af96e6f347b738a28c84ffb9db61617e812b21c51c8b29d9d8d1c92d2df9b386a2404c394  
 ---------------------------------- ---------------------------------------------------------------------------------------------------------------------------------- 
```

## Update a client
```bash

bin/console trikoder:oauth2:update-client --grant-type client_credentials --grant-type password c0a71bf0379c66c46da3ed41a4f4aab2
```

## List client
```bash
bin/console trikoder:oauth2:list-clients
```

## Get token
Using a token with password protocol:

```bash
curl --location --request POST 'localhost/token' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'grant_type=password' \
--data-urlencode 'username=bob' \
--data-urlencode 'password=xyz' \
--data-urlencode 'client_id=c0a71bf0379c66c46da3ed41a4f4aab2' \
--data-urlencode 'client_secret=e8f9855c30bb9915e61bc093656e75c7e8e3bc3b221eca2be796790af96e6f347b738a28c84ffb9db61617e812b21c51c8b29d9d8d1c92d2df9b386a2404c394'
```

## Get User Into

Userinfo is availabel at `localhost/userinfo`.

```bash
curl --location --request GET 'localhost/userinfo' --header 'Authorization: Bearer {token}'


{
    
    "_id": "6043d6703e01b17faf118b53",
    "username": "bob",
    "nome": "Bobby" 
     /* any data here*/
}
```

# GraphQL

Endpoint is located at `localhost/api/graph/{db}`

```bash
query { 
  entity1{
      _id,
      title,
      amount
  }
}
```

```json
{
    "data": {
        "entity1": [
            {
                "_id": "6045038ae0c6cde18c3c93bf",
                "title": "test data",
                "amount": 1000
            }
        ]
    }
}
```







# Code lambda

Endpoint is located at `localhost/api/do/{db}/{action}/`


```bash
curl --location --request GET 'localhost/api/do/{db}/{action}/' 



{
    "_id" : ObjectId("60452b54200a9b44267324e8"),
    "name" : "test2",
    "code" : "return $container['crud']->find('test','_mutations',[],1,10);"
}
```



# Setup & Testing

```bash

#create the client oauht2
docker-compose exec app bin/console trikoder:oauth2:create-client --grant-type password c0a71bf0379c66c46da3ed41a4f4aab2

docker-compose exec app bin/console  app:crud:upsert test "_users" '{"username":"admin","newpassword":"password"}'
#create an entity with fields title and amount
docker-compose exec app bin/console  app:crud:upsert test '_schemas' '{ "name" : "entity1","db" :"test","fields":{"title":{"type": "text",            "name" : "title","label" : "Title"},"amount" : {"type" : "int", "name" : "amount","label" : "Amount"}}}'

#get token
curl --location --request POST 'localhost/token' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'grant_type=password' \
--data-urlencode 'username=admin' \
--data-urlencode 'password=password' \
--data-urlencode 'client_id={replace}' \
--data-urlencode 'client_secret={replace}'

# enter data
docker-compose exec app bin/console  app:crud:upsert test "entity1" '{"title":"title 1","amount":100}'
docker-compose exec app bin/console  app:crud:upsert test "entity1" '{"title":"title 2","amount":101}'
docker-compose exec app bin/console  app:crud:upsert test "entity1" '{"title":"title 3","amount":102}'
docker-compose exec app bin/console  app:crud:upsert test "entity1" '{"title":"title 4","amount":103}'

#get data GraphQL
curl --location --request GET 'localhost/api/graph/test' --header 'Content-Type: application/json' --data-raw '{"query":"query { \r\n  entity1{\r\n      _id,\r\n      title,\r\n      amount\r\n  }\r\n}","variables":{}}'

curl --location --request GET 'http://localhost/api/data/test/entity1' \
--header 'Authorization: Bearer {token}'

```