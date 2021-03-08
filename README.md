{"filed":"value"}

bin/console app:crud:upsert test test {\"filed\":\"value\"}
bin/console app:crud:find test test {\"filed\":\"value\"}
bin/console app:crud:delete test test {\"filed\":\"value\"}



http://localhost/api/data/test/prova?skip=10&limit=20&query={%22title%22:%22value%22}

```
{"request":{"database":"test","collection":"test","skip":"10","limit":"20","query":"{}"},"data":[{"_id":"604335dcddfd8c484b4da692","title":"mytitle","body":"mybody"},{"_id":"604335ea05dcae70fe1ed752","title":"mytitle","body":"mybody"},{"_id":"604335f88288e669541ccb72","title":"mytitle","body":"mybody"},{"_id":"6043363cf9265b45a810f262","title":"mytitle","body":"mybody"},{"_id":"60433646825e954b41534232","title":"mytitle","body":"mybody"},{"_id":"6043368e22619b6cd151d852","title":"mytitle","body":"mybody"},{"_id":"6043369ca48c2a285d4682f2","title":"mytitle","body":"mybody"},{"_id":"604336f26e205328b82ddd32","title":"mytitle","body":"mybody"},{"_id":"6043370630658259257fd982","title":"mytitle","body":"mybody"},{"_id":"60433722f915fc6ba00f5292","title":"mytitle","body":"mybody"},{"_id":"6043387ef42a7a265a743c02","filed":"value"}],"metadata":[]}
```


http://localhost/api/data/test/test/60428383bdb90439ec593df2

```
{"request":{"database":"test","collection":"test","id":"60428383bdb90439ec593df2"},"data":{"_id":"60428383bdb90439ec593df2","ddd":"ddd"},"metadata":[]}
```



# oauth

 bin/console trikoder:oauth2:create-client

 -------- 
  Identifier                         Secret                                                                                                                            
 ---------------------------------- ---------------------------------------------------------------------------------------------------------------------------------- 
  c0a71bf0379c66c46da3ed41a4f4aab2   e8f9855c30bb9915e61bc093656e75c7e8e3bc3b221eca2be796790af96e6f347b738a28c84ffb9db61617e812b21c51c8b29d9d8d1c92d2df9b386a2404c394  
 ---------------------------------- ---------------------------------------------------------------------------------------------------------------------------------- 

 bin/console trikoder:oauth2:update-client --grant-type client_credentials --grant-type password c0a71bf0379c66c46da3ed41a4f4aab2

  bin/console trikoder:oauth2:list-clients


curl --location --request POST 'localhost/token' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'grant_type=password' \
--data-urlencode 'username=bob' \
--data-urlencode 'password=xyz' \
--data-urlencode 'client_id=c0a71bf0379c66c46da3ed41a4f4aab2' \
--data-urlencode 'client_secret=e8f9855c30bb9915e61bc093656e75c7e8e3bc3b221eca2be796790af96e6f347b738a28c84ffb9db61617e812b21c51c8b29d9d8d1c92d2df9b386a2404c394'


localhost/userinfo

curl --location --request GET 'localhost/userinfo' --header 'Authorization: Bearer {token}'


{
    "data": {
        "_id": "6043d6703e01b17faf118b53",
        "username": "bob",
        "nome": "Prova"
    },
    "username": "bob"
}


# GraphQL

Endpoint:

localhost/api/graph/test


query { 
  entity1{
      _id,
      title,
      amount
  }
}


{
    "data": {
        "entity1": [
            {
                "_id": "6045038ae0c6cde18c3c93bf",
                "title": "prova",
                "amount": 1000
            }
        ]
    }
}






# Rest APIs


curl --location --request POST 'localhost/api/data/{db}/{collection}' --data-raw '{"field": "data"}'


curl --location --request PUT 'localhost/api/data/{db}/{collection}/id' --data-raw '{"field": "data"}'


curl --location --request DELETE 'localhost/api/data/{db}/{collection}/id' 

curl --location --request GET 'localhost/api/data/{db}/{collection}/id' 



# Code lambda


curl --location --request GET 'localhost/api/do/{db}/{action}/' 



{
    "_id" : ObjectId("60452b54200a9b44267324e8"),
    "name" : "test2",
    "code" : "return $container['crud']->find('test','_mutations',[],1,10);"
}




