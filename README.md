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

