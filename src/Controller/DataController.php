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
              'method' =>'post'
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
              'method' =>'put'
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
              'delete'=>"delete"
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
              'method' =>'get'
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
      $skip=$request->get("skip");
      $limit=$request->get("limit");
      $query=$request->get("query");

      $filter=json_decode($query??"",true);

      $data=$this->service->find($database,$collection,$filter);

        //TODO: metadata computazioni...
        return new JsonResponse(array(
          "request"=>array(
            'database' =>$database,
            'collection' => $collection,
            'skip' => $skip,
            'limit' => $limit,
            'query' => $query,
          ),
          'data'=> $data,
          'metadata' =>[]
        ));
    }

}
