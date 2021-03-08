<?php

namespace App\Entity;

use Exception;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;

class AbstractField implements IFieldType
{
    public function getType(): String
    {
        throw new  Exception("NOT IMPLEMENTED");
    }

    public  function validate($item, $fieldName, $value,$settings): array
    {
        $validate=array();
        if(empty($value) && !key_exists("required", $settings) && $settings["required"]) 
        {
            $validate[]="Required value for field $fieldName";
        }        
        return $validate;
    }

    public  function getGrapQlType():ScalarType
    {
       return Type::string();
    }
}