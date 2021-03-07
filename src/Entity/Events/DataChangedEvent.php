<?php

namespace App\Entity\Events;

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

    public function __construct($data,$previousData,$operation)
    {
        $this->data=$data;  
        $this->previousData=$previousData;
        $this->operation=$operation;
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