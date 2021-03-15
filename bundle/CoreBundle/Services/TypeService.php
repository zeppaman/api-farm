<?php

namespace Apifarm\CoreBundle\Services;

use Apifarm\CoreBundle\Entity\IFieldType;

class TypeService implements ITypeService
{
    private $types=array();

    public function __construct(iterable $types)
    {
        foreach($types as $type)
        {           
            $this->types[$type->getType()]=$type;
        }
    }

    function getTypeDefinition(String $type) : IFieldType
    {
      
        return  $this->types[$type];
    }

    function addTypeDefinition(IFieldType $type): void
    {
        
      //  $this->types[$type->getType()]=$type;
    }
}