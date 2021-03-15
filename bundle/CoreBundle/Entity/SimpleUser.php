<?php

namespace Apifarm\CoreBundle\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

class SimpleUser implements UserInterface 
{
    public $data;

    public $username;

    public function __construct($username, $data)
    {
       $this->data=$data;
       $this->username=$username;
    }
   
    public function getRoles(){
        return array("ROLE_ADMIN");
    }

  
    public function getPassword(){
        return   $this->data["password"];
    }


    public function getSalt(){
        return "no-salt";
    }

    
    public function getUsername(){
        return $this->username;
    }

    public function eraseCredentials(){
        unset($this->data["password"]);
    }

   

    public function getProperty($name)
    {
        return $this->data[$name];
    }
}