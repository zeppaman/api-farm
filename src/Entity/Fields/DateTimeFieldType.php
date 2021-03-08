<?php

namespace App\Entity\Fields;

use App\Entity\AbstractField;
use App\Entity\GraphQL\Type\DateTimeType;
use App\Entity\IFieldType;
use GraphQL\Type\Definition\IntType;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use Stagem\GraphQL\Type\DateTimeType as TypeDateTimeType;

class TextFieldType extends AbstractField
{
    public function getType(): String
    {
        return "date";
    }

    public  function validate($item, $fieldName, $value,$settings): array
    {
        $validate=array();
        if(empty($value) && !key_exists("required", $settings) && $settings["required"]) 
        {
            $validate[]="Required value for field $fieldName";
        }
        else
        {
        
        }
        return $validate;
    }

    public  function getGrapQlType():ScalarType
    {        
        return  new TypeDateTimeType();
    }
    
}