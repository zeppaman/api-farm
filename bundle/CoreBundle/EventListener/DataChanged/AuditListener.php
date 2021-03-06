<?php

namespace Apifarm\CoreBundle\EventListener\DataChanged;

use Apifarm\CoreBundle\Entity\Events\DataChangedEvent;
use DateTime;
use Symfony\Contracts\EventDispatcher\Event;

class AuditListener
{
  

    public function onPreSave(DataChangedEvent $event)
    {
        if($event->getOperation()== DataChangedEvent::PREADD)
        {            
            $event->data["created"]= (new DateTime())->format(DateTime::ATOM);
        }

        $event->data["updated"]= (new DateTime())->format(DateTime::ATOM);
    }
}