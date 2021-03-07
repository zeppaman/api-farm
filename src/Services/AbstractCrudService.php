<?php

namespace App\Services;

use App\Entity\Events\DataChangedEvent;
use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use MongoDB\InsertOneResult;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AbstractCrudService implements ICrudService
{
    private $client;
    private $typeService;
    private $dispatcher;
    public function __construct(Client $client, TypeService $typeService, EventDispatcherInterface $dispatcher)
    {
        $this->client=$client;            
        $this->typeService=$typeService;
        $this->dispatcher=$dispatcher;
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
        $event=new DataChangedEvent($data,null,DataChangedEvent::PREADD);
        $this->dispatcher->dispatch($event, DataChangedEvent::NAME);
        $data=$event->getData();

        $db= $this->client->selectDatabase($db);            
        $coll= $db->selectCollection($collection);
        unset($data["_id"]);
        
        $result=$coll->insertOne($data);
        $id=$result->getInsertedId();        
        $result = $this->get($db,$collection,$id);

        $this->dispatcher->dispatch(new DataChangedEvent($result,$data,DataChangedEvent::POSTADD), DataChangedEvent::NAME);

        return $result;
    }

    function update(String $db,String $collection, $data,bool $replace=false)
    {
        $event=new DataChangedEvent($data,null,DataChangedEvent::PREUPDATE);
        $this->dispatcher->dispatch($event, DataChangedEvent::NAME);
        $data=$event->getData();

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
        $result= $this->get($db,$collection,$id);
        $this->dispatcher->dispatch(new DataChangedEvent($result,$data,DataChangedEvent::POSTADD), DataChangedEvent::NAME);
        return $result;
    }

    function delete(String $db, String $collection,String $id)
    {
        $this->dispatcher->dispatch(new DataChangedEvent($id,null,DataChangedEvent::PREDELETE), DataChangedEvent::NAME);


        $db= $this->client->selectDatabase($db);            
        $coll= $db->selectCollection($collection);       
     
        $coll->deleteOne($this->getIdFilter($id));

        $this->dispatcher->dispatch(new DataChangedEvent(null,null,DataChangedEvent::POSTDELETE), DataChangedEvent::NAME);       
    }

    function find(String $db,String $collection,$query=[], $skip=0, $limit=1000, $sort=array())
    {
        $db= $this->client->selectDatabase($db);            
        $coll= $db->selectCollection($collection);      
        $result= $coll->find($query,array(
            'skip' =>$skip,
            'limit' =>$limit,
            'sort' => $sort
        ));

        return $this->transfromMany($result);
    }


    function getSchema(String $db,String $collection=null)
    {
        $filter=array("name"=>$collection, "db"=>$db);

        if(empty($collection))
        {
            unset($filter["name"]);
        }
        $items= $this->find($db,"_schema", $filter);
        if(!empty($items) && sizeof($items)==1)
        {
            return $items[0];
        }
        return $items;
        return null;       

    }

    function validate(String $db,String $collection, $item)
    {
        //TODO: Once per service
        $schema=$this->getSchema( $db, $collection);

        if(!empty($schema))
        {
            $errors=[];
            if(key_exists("fields",$schema))
            {
                $fields=$schema["fields"];
                foreach($fields as $key=>$value)
                {
                    $type=$this->typeService->getTypeDefinition($value["type"]);
                    if($type)
                    {
                        $errors[]= $type->validate($item,$key,$item[$key],$value["settings"]);
                    }
                }
            }

            return $errors;
        }

        return [];
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