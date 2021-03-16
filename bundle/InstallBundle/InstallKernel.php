<?php

namespace Apifarm\InstallBundle;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class InstallKernel extends BaseKernel
{
    use MicroKernelTrait;
    protected function configureContainer(ContainerConfigurator $container): void
    {
        //$container->import('config/*.yaml');
       // $container->import('config/packages/farmwork.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
    }
    public function getProjectDir(): string
    {
        return __DIR__;
    }
}