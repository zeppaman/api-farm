<?php

namespace App\EventListener\DataChanged;

use App\Entity\Events\DataChangedEvent ;
use App\Entity\SimpleUser;
use DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\Event;

class PasswordEncoderListener
{

    protected $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
  
    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    
    public function onPreSave(DataChangedEvent $event)
    {
  
       if($event->entity =="_users")
       {          
           if($event->operation == DataChangedEvent::PREADD)
           {
                if(!isset($event->data["salt"]))
                {
                    $event->data["salt"]=$this->generateRandomString(10);
                }
           }
           if(isset($event->data["newpassword"]))
           {
               
               $pwd=$event->data["newpassword"];
               $user=new SimpleUser($event->data["username"],array());
               $encrypted=$this->encoder->encodePassword($user,$pwd);
               $event->data["password"]=$encrypted;
               unset($event->data["newpassword"]);
           }
       }
    }
}