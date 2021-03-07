<?php

namespace App\Entity;

use GraphQL\Type\Definition\ScalarType;

//App\Entity\IFieldType

interface IFieldType
{
    public function getType():String;

    public  function validate($item, $fieldName, $value,$settings) : array;

    public  function getGrapQlType():ScalarType;
    
}