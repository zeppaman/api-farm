<?php

namespace Apifarm\CoreBundle;

use Apifarm\CoreBundle\DependencyInjection\CoreExtension;
use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CoreBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new CoreExtension();
    }

    public function getPath(): string
    {
       // echo \dirname(__DIR__); exit;
        return __DIR__;
    }


    
    public function build(ContainerBuilder $container)
    {       
        $requiredBundles = [
            'security' => \Symfony\Bundle\SecurityBundle\SecurityBundle::class,
            'trikoder_oauth2' =>TrikoderOAuth2Bundle::class,
            'lexik_jwt_authentication' =>LexikJWTAuthenticationBundle::class
        ];
        $extension = $container->getExtension('security');
        //echo print_r( $extension, true);exit;
        // foreach ($requiredBundles as $bundleAlias => $requiredBundle) {
        //     if (!$container->hasExtension($bundleAlias)) {
        //         throw new Exception(sprintf('Bundle \'%s\' needs to be enabled in your application kernel.', $requiredBundle));
        //     }
        // }
    }

    public function process(ContainerBuilder $container)
    {       
        $requiredBundles = [
            'security' => \Symfony\Bundle\SecurityBundle\SecurityBundle::class,
            'trikoder_oauth2' =>TrikoderOAuth2Bundle::class,
            'lexik_jwt_authentication' =>LexikJWTAuthenticationBundle::class
        ];

        foreach ($requiredBundles as $bundleAlias => $requiredBundle) {
            if (!$container->hasExtension($bundleAlias)) {
                throw new Exception(sprintf('Bundle \'%s\' needs to be enabled in your application kernel.', $requiredBundle));
            }
        }
    }
}