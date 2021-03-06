<?php

namespace App\EventListener;

use App\Entity\SimpleUser;
use App\Services\ICrudService;
use MongoDB\Model\BSONDocument;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Trikoder\Bundle\OAuth2Bundle\Event\UserResolveEvent;
use Trikoder\Bundle\OAuth2Bundle\Manager\ClientFilter;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;
use Trikoder\Bundle\OAuth2Bundle\Manager\ClientManagerInterface;
use Trikoder\Bundle\OAuth2Bundle\OAuth2Events;


class UserResolveListener
{
    public $encoder;
    public $service;
    public function __construct(UserPasswordEncoderInterface $encoder, ICrudService $service)
    {
        $this->encoder=$encoder;
        $this->service=$service;
    }

    public function onUserResolve(UserResolveEvent $event): void
    {
        $username=$event->getUsername(); 
        $users= $this->service->find("test","_users",array("username" =>  $username) ,0,1);

        if(empty($users))
        {
          return;
        } 

        
        $data=get_object_vars($users[0]->jsonSerialize());
        

        $user=new SimpleUser($users[0]["username"],$data);        
        
    
        $entered=$this->encoder->encodePassword($user,$event->getPassword());
        $comparison=$user->getProperty("password");

        // echo print_r(  $entered, true);
        // echo print_r(  "\n\n", true);
        // echo print_r(  $comparison, true);
        // exit;

        if($entered!==$comparison)
        {
            echo "different passwords";
            return;
        }        

        $event->setUser( $user);

        
    }
}
