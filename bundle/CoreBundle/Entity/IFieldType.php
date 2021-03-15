<?php

namespace Apifarm\CoreBundle\Entity;

use GraphQL\Type\Definition\ScalarType;

interface IFieldType
{
    public function getType():String;

    public  function validate($item, $fieldName, $value,$settings) : array;

    public  function getGrapQlType():ScalarType;
    
}