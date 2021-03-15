<?php

namespace Apifarm\CoreBundle\Entity\Events;

use Symfony\Contracts\EventDispatcher\Event;

class DataChangedEvent extends Event
{
    public const NAME = 'app.crud.datachanged';

    public const PREADD = 'PREADD';
    public const POSTADD = 'POSTADD';

    public const PREUPDATE = 'PREUPDATE';
    public const POSTUPDATE = 'POSTUPDATE';

    public const PREDELETE = 'PREDELETE';
    public const POSTDELETE = 'POSTDELETE';


    public $data=array();
    public $previousData;
    public $operation;
    public $db;
    public $entity;

    public function __construct($data,$previousData,$operation, $db,$entity)
    {
        $this->data=$data;  
        $this->previousData=$previousData;
        $this->operation=$operation;
        $this->db=$db;
        $this->entity=$entity;

    }

    public function getData()
    {
        return $this->data;
    }

    public function getPreviousData()
    {
        return $this->previousData;
    }

    public function getOperation(): String
    {
        return $this->operation;
    }
    
}