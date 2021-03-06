<?php

namespace App\Security;

use App\Services\ICrudService;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Trikoder\Bundle\OAuth2Bundle\Event\UserResolveEvent;
use Trikoder\Bundle\OAuth2Bundle\Manager\AccessTokenManagerInterface;
use Trikoder\Bundle\OAuth2Bundle\Manager\ClientFilter;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;
use Trikoder\Bundle\OAuth2Bundle\Manager\ClientManagerInterface;
use Trikoder\Bundle\OAuth2Bundle\Model\Grant;
use Trikoder\Bundle\OAuth2Bundle\Model\Scope;
use Trikoder\Bundle\OAuth2Bundle\Model\AccessToken;



class AccessTokenManager implements AccessTokenManagerInterface
{

    protected $service;
    protected $clientService;
    public function __construct(ICrudService $service, ClientManager $clientService)
    {
        $this->service=$service;
        $this->clientService=$clientService;
    }
    
    public function find(string $identifier): ?AccessToken
    {
        $tokens=$this->service->find("test","_tokens", array("identifier"=>$identifier),0,1,array('expiry'=>-1));
        
        foreach($tokens as $token)
        {
           return $this->mapOne($token);
        }
        return null;
    }

    public function save(AccessToken $accessToken): void
    {
        $data=array(
            "identifier"=> $accessToken->getIdentifier(),
            "user" =>$accessToken->getUserIdentifier(),
            "expiry" =>$accessToken->getExpiry()->getTimestamp(),
            "client" => $accessToken->getClient()->getIdentifier()
        );
  
       
        $scopes=$accessToken->getScopes();
        foreach($scopes as $scope)
        {
          $data["scopes"].=$scope->__toString().",";
        }  
  
        $clients=$this->service->find("test","_tokens", array("identifier"=>$data["identifier"]));
  
        if($clients)
        {
            $data["_id"]=$clients[0]["_id"];
           
            $this->service->update("test","_tokens",$data,true);
        }
        else
        {
         
          $this->service->add("test","_tokens",$data);
        }
  
    }

    function mapOne($item)
    {
        $scopes=[];
        if( isset($item["scopes"]))
        {
            $scopesStr= $item["scopes"];
        
            foreach($scopesStr as $scope)
            {
                if(!empty($scope ))
                {
                    $scopes[]=new Scope($scope);
                }   
            }           
        }

        $token= new AccessToken($item["identifier"],
        (new DateTime())->setTimestamp($item["expiry"]),
        $this->clientService->find($item["client"]),
        $item["user"],        
        $scopes
        );
            
        return $token;
    }
    public function clearExpired(): int
    {
        return 0;
    }

    public function clearRevoked()
    {        
        return 0;
    }
}
