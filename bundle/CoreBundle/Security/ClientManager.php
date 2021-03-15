<?php

namespace Apifarm\CoreBundle\Security;

use Apifarm\CoreBundle\Services\ICrudService;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Trikoder\Bundle\OAuth2Bundle\Event\UserResolveEvent;
use Trikoder\Bundle\OAuth2Bundle\Manager\ClientFilter;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;
use Trikoder\Bundle\OAuth2Bundle\Manager\ClientManagerInterface;
use Trikoder\Bundle\OAuth2Bundle\Model\Grant;
use Trikoder\Bundle\OAuth2Bundle\Model\RedirectUri;
use Trikoder\Bundle\OAuth2Bundle\Model\Scope;



//ClientManagerInterface

class ClientManager implements ClientManagerInterface
{
    protected $service;
    public function __construct(ICrudService $service)
    {
        $this->service=$service;
    }

    public function find(string $identifier): ?Client
    {
        $clients=$this->service->find("test","_clients", array("identifier"=>$identifier));
        
        foreach($clients as $client)
        {
           return $this->mapOne($client);
        }
        return null;
       
    }

    public  function mapOne($client): Client
    {
        $result= new Client($client["identifier"], $client["secret"]);
            
            $grantsStr=explode(",",$client["grants"]);

            $grants=[];
            foreach($grantsStr as $grant)
            {
                if(!empty($grant ))
                {
                    $grants[]=new Grant($grant);
                }
            }
            
            $result->setGrants(...$grants);


            $scopesStr=explode(",",$client["scopes"]);

            $scopes=[];
            foreach($scopesStr as $scope)
            {
                if(!empty($scope ))
                {
                    $scopes[]=new Scope($scope);
                }   
            }           

            $result->setScopes(...$scopes);

            $uriStr=explode(",",$client["uris"]);
            $uris=[];
            foreach($uriStr as $uri)
            {
                if(!empty($uri ))
                {
                    $uris[]=new RedirectUri($uri);
                }
            }            

            $result->setRedirectUris(...$uris);

            $result->setActive($client["active"]);
            return $result;
    }

    public function save(Client $client):void
    {
      $data=array(
          "identifier"=> $client->getIdentifier(),
          "secret" =>$client->getSecret(),
          "grants" =>"",
          "scopes" =>"",
          "uris" =>"",
          "active" =>$client->isActive()
      );

      $uris=$client->getRedirectUris();
      foreach($uris as $uri)
      {
        $data["uris"].=$uri->__toString().",";
      }
      $grants=$client->getGrants();
      foreach($grants as $grant)
      {
        $data["grants"].=$grant->__toString().",";
      }
      $scopes=$client->getScopes();
      foreach($scopes as $scope)
      {
        $data["scopes"].=$scope->__toString().",";
      }


      $clients=$this->service->find("test","_clients", array("identifier"=>$data["identifier"]));

      if($clients)
      {
          $data["_id"]=$clients[0]["_id"];
         
          $this->service->update("test","_clients",$data,true);
      }
      else
      {
       
        $this->service->add("test","_clients",$data);
      }


        
    }

    public function remove(Client $client):void
    {
        $clients=$this->service->find("test","_clients", array("identifier"=>$client->getIdentifier()));
        if(sizeof($clients)>0)
        {
            $this->service->delete("test","_clients",$clients[0]["_id"]);
        }

    }

    /**
     * @return Client[]
     */
    public function list(?ClientFilter $clientFilter):array
    {
        $clients=$this->service->find("test","_clients", []);
        
        $results=[];
        foreach($clients as $client)
        {
            $results[]=$this->mapOne($client);
        }
        return  $results;
    }
}
