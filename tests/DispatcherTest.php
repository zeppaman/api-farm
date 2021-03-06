<?php
namespace App\Tests;

use App\Events\DataChangedEvent as EventsDataChangedEvent;
use App\Entity\Events\Dispatcher;
use App\Events\DataChangedEvent;
use App\Services\CrudService;
use DateTime;
use Laminas\EventManager\Event;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DispatcherTest extends KernelTestCase
{

    function testDispatch()
    {
        
        $kernel = static::createKernel();
        static::bootKernel();
        $application = new Application($kernel);

        $event= new DataChangedEvent();
        $dispatcher=self::$container->get(Dispatcher::class);
        

        $dispatcher->addListener(DataChangedEvent::NAME, function (DataChangedEvent $event) {
            print_r($event);
            $event->getData();
        });
        

        $dispatcher->dispatch($event, DataChangedEvent::NAME);

        

        $this->assertEquals($event->getData(), "changed");

    }

    function testMainDispatcher()
    {
        $kernel = static::createKernel();
        static::bootKernel();   
        $application = new Application($kernel);

        $dispatcher=self::$container->get(EventDispatcherInterface::class);
        
        $dispatcher->dispatch(new DataChangedEvent(null,null,null), DataChangedEvent::NAME);
    }
}