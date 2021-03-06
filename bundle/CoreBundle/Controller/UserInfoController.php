<?php


namespace Apifarm\CoreBundle\Controller;

use Apifarm\CoreBundle\Entity\SimpleUser;
use Apifarm\CoreBundle\Services\ICrudService;
use MongoDB\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserInfoController extends AbstractController
{
    private $service;

    public function __construct(ICrudService $service )
    {
        $this->service=$service;
    }
     /**
     * @Route("/userinfo", name="userinfo", methods={"GET"})
     */
    public function info(Request $request)
    {
        $username= $this->getUser()->getUsername();

        $users= $this->service->find("config","_users",array("username" => $username),0,1);

        $data=get_object_vars($users[0]->jsonSerialize());
        unset($data["password"]);

        if(empty($users))
        {         
         
            return new JsonResponse($data);
        } 
        
        return new JsonResponse( new SimpleUser($username,$data));
    }
}