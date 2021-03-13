<?php

namespace App\Controller;

use App\Services\ICrudService;
use MongoDB\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DataController extends AbstractController
{

   protected $service;

   public function __construct(ICrudService $service)
   {
     $this->service=$service;
   }


     /**
     * @Route("/api/data/{database}/{collection}", name="crud_add", methods={"POST"})
     */
    public function insert(Request $request,$database,$collection)
    {
      $item=json_decode($request->getContent(), true);
      
      $data=$this->service->add($database,$collection,$item);


        //TODO: metadata computazioni...
          return new JsonResponse(array(
            "request"=>array(
              'database' =>$database,
              'collection' => $collection,
              'method' =>'post',
              'username' => $this->getUser()->getUsername()
            ),
            'data'=> $data,
            'metadata' =>[]
          ));
    }


     /**
     * @Route("/api/data/{database}/{collection}/{id}", name="crud_update", methods={"PUT"})
     */
    public function update(Request $request,$database,$collection,$id)
    {
      $replace=$request->get("replace",true);
      $item=json_decode($request->getContent(), true);
      $item["_id"]=$id;
      
      $data=$this->service->update($database,$collection,$item, $replace);

        //TODO: metadata computazioni...
          return new JsonResponse(array(
            "request"=>array(
              'database' =>$database,
              'collection' => $collection,
              'method' =>'put',
              'username' => $this->getUser()->getUsername()
            ),
            'data'=> $data,
            'metadata' =>[]
          ));
    }


    /**
     * @Route("/api/data/{database}/{collection}/{id}", name="crud_delete", methods={"DELETE"})
     */
   public function delete(Request $request,$database,$collection,$id)
    {
      $data=$this->service->delete($database,$collection,$id);

        //TODO: metadata computazioni...
          return new JsonResponse(array(
            "request"=>array(
              'database' =>$database,
              'collection' => $collection,
              'id'=>$id,
              'delete'=>"delete",
              'username' => $this->getUser()->getUsername()
            ),
            'data'=> $data,
            'metadata' =>[]
          ));
    }

    /**
     * @Route("/api/data/{database}/{collection}/{id}", name="crud_get", methods={"GET"})
     */
    public function getById(Request $request,$database,$collection,$id)
    {
      $data=$this->service->get($database,$collection,$id);

        //TODO: metadata computazioni...
          return new JsonResponse(array(
            "request"=>array(
              'database' =>$database,
              'collection' => $collection,
              'id'=>$id,
              'method' =>'get',
              'username' => $this->getUser()->getUsername()
            ),
            'data'=> $data,
            'metadata' =>[]
          ));
    }
    /**
     * @Route("/api/data/{database}/{collection}", name="crud_find")
     */
    public function find(Request $request,$database,$collection)
    {
      $skip=$request->get("skip",0);
      $limit=$request->get("limit",1000);
      $query=$request->get("query","{}");
      $sort=$request->get("sort",array());

      $filter=json_decode($query??"",true);

      $data=$this->service->find($database,$collection,$filter,$skip,$limit,$sort);

        //TODO: metadata computazioni...
        return new JsonResponse(array(
          "request"=>array(
            'database' =>$database,
            'collection' => $collection,
            'skip' => $skip,
            'limit' => $limit,
            'query' => $query,
            'username' => $this->getUser()->getUsername()
          ),
          'data'=> $data,
          'metadata' =>[]
        ));
    }


      /**
     * @Route("/api/do/{db}/{name}", name="mutate", methods={"GET"})
     */
    public function mutate(Request $request, $db,$name)
    {
        $items= $this->service->find($db,"_mutations",array("name"=>$name),0,1);
        if(!empty($items))
        {
            $exec= $items[0];
            $code=$exec["code"];
            $container["crud"]=$this->service;            
            $function=function($container,$request){};
            eval("\$function = function(\$container, \$request){ $code};");
            $result=$function($container,$request);
            return new JsonResponse($result);
        }
        return null;
    }
}

