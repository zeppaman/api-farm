<?php

namespace App\Services;

use App\Entity\IFieldType;

interface ITypeService 
{
    
    public  function getTypeDefinition(String $type) : IFieldType;    

    public    function addTypeDefinition(IFieldType $type): void;  
   
}