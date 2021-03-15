<?php

namespace Apifarm\CoreBundle\Security;

use Apifarm\CoreBundle\Entity\SimpleUser;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface as StorageTokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Trikoder\Bundle\OAuth2Bundle\Event\UserResolveEvent;
use Trikoder\Bundle\OAuth2Bundle\Manager\ClientFilter;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;
use Trikoder\Bundle\OAuth2Bundle\Manager\ClientManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class  UserProvider implements PayloadAwareUserProviderInterface
{

    protected $enabledUserInfo;
    protected $storage;
    protected $requestStack;
        
    public function __construct(TokenStorageInterface $storage,RequestStack $requestStack, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->storage=$storage;
        $this->requestStack = $requestStack;
    }

    public function enableUserInfo(bool $enable)
    {
        $this->enabledUserInfo=$enable;
    }

   
    public function loadUserByUsernameAndPayload($username, array $payload)
    {        

        //userinfo ror standalone mode falls here
        if(str_starts_with($this->requestStack->getCurrentRequest()->getRequestUri(),"/userinfo"))
        {
            return new SimpleUser($username,$payload);
        }

        $data=$payload;

        //get from user info
        if($this->enabledUserInfo || true)
        {
            $token= $this->requestStack->getCurrentRequest()->headers->get("Authorization");

            // echo $this->requestStack->getCurrentRequest()->getRequestUri(); 
            $host= $this->requestStack->getCurrentRequest()->getHost(); 
            $scheme= $this->requestStack->getCurrentRequest()->getScheme(); 
            // exit;

            $response=$this->client->request(
                'GET',
                "$scheme://$host/userinfo",[
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' =>  $token,
                    ]
                ]
            );
    
            $content= $response->getContent();
           
            $info = json_decode( $content, true);
            $data=array_merge($payload,$info);

        }
        
        return  new SimpleUser($username, $data);
    }

    public function loadUserByUsername(string $username)
    {
      
        return null;
    }

  
    public function refreshUser(UserInterface $user)
    {

    }

  
    public function supportsClass(string $class)
    {

    }
}