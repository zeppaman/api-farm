<?php

namespace Apifarm\CoreBundle\Controller;

use Apifarm\CoreBundle\Services\ICrudService;
use MongoDB\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthTestController extends AbstractController
{

     /**
     * @Route("/api/test", name="test", methods={"GET"})
     */
    public function test( TokenStorageInterface $token,Request $request)
    {      

      
      return new JsonResponse( array(
        "token"=>$token->getToken()->__toString(),
        "username"=>$this->getUser()->getUsername(),
         "class" =>$this->getUser()->data,
         "token"=>$request->headers->get("Authorization")));
    }
}

