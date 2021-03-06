<?php

namespace App\Services;

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use MongoDB\InsertOneResult;


class AbstractCrudService implements ICrudService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client=$client;    
    }

    function get(String $db,String $collection,String $id)
    {
        
        $db= $this->client->selectDatabase($db);            
        $coll= $db->selectCollection($collection);
        $item=$coll->findOne($this->getIdFilter($id));
        return $this->transformOne($item);
    }

    function add(String $db,String $collection, $data)
    {
        $db= $this->client->selectDatabase($db);            
        $coll= $db->selectCollection($collection);
        unset($data["_id"]);
        $result=$coll->insertOne($data);
        $id=$result->getInsertedId();
        return $this->get($db,$collection,$id);
    }

    function update(String $db,String $collection, $data,bool $replace=false)
    {
        $db= $this->client->selectDatabase($db);            
        $coll= $db->selectCollection($collection);
       
        //rebuild BSON ID
        $id=$data["_id"];
        $data["_id"]= new ObjectId($id);

        $options=array(
            'upsert' => false
        );
       
        if($replace)
        {
            $coll->replaceOne($this->getIdFilter($id), $data,$options);
        }
        else
        {
            $coll->updateOne($this->getIdFilter($id), $data,$options);
        }
        return $this->get($db,$collection,$id);
    }

    function delete(String $db, String $collection,String $id)
    {
        $db= $this->client->selectDatabase($db);            
        $coll= $db->selectCollection($collection);       
     
        $coll->deleteOne($this->getIdFilter($id));
    }

    function find(String $db,String $collection,$query=[], $skip=0, $limit=1000)
    {
        $db= $this->client->selectDatabase($db);            
        $coll= $db->selectCollection($collection);      
        $result= $coll->find($query,array(
            'skip' =>$skip,
            'limit' =>$limit
        ));

        return $this->transfromMany($result);
    }

    public function transfromMany($items)
    {
        $result=[];
        foreach($items as $item)
        {
            $result[]= $this->transformOne($item);
        }
        return $result;
    }
    public function transformOne($item)
    {
        if(empty($item)) return null;

        $itemId=$item["_id"];
        $item["_id"] = $item["_id"]->__toString ( );
        return $item;
    }

    public function getIdFilter($id)
    {
      return  [
            '_id' => new \MongoDB\BSON\ObjectId($id),
      ];
    }
}