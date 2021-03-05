<?php

namespace App\Controller;

use MongoDB\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class DataController extends AbstractController
{
    /**
     * @Route("/api/data", name="data_list")
     */
  public function data_list(Request $request)
  {
      $collection=$request->get("collection");
      $query=$request->get("query");


      $client = new Client(
        'mongodb://mongo/test?retryWrites=true&w=majority'
        );        
      
        $db= $client->selectDatabase("test");            
        $coll= $db->selectCollection($collection);
        $all=$coll->find();    
        $items=[];
        foreach($all as $doc)
        {
            $items[]=$doc;
        } 
        return new JsonResponse($items);
  }

}
