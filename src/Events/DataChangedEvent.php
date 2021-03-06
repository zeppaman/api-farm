<?php

namespace App\Events;

use Symfony\Contracts\EventDispatcher\Event;

class DataChangedEvent extends Event
{
    public const NAME = 'crud.datachanged';

    protected $data="ee";

    public function __construct()
    {
       
    }

    public function getData(): String
    {
        return $this->data;
    }

    public function setData($data)
    {
         $this->data=$data;
         echo "SETTED";
    }
}