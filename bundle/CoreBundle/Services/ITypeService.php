<?php

namespace Apifarm\CoreBundle\Services;

use Apifarm\CoreBundle\Entity\IFieldType;

interface ITypeService 
{
    
    public  function getTypeDefinition(String $type) : IFieldType;    

    public    function addTypeDefinition(IFieldType $type): void;  
   
}