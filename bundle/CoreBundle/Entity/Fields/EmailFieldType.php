<?php

namespace Apifarm\CoreBundle\Entity\Fields;

use Apifarm\CoreBundle\Entity\AbstractField ;

use GraphQL\Type\Definition\ScalarType;
use Stagem\GraphQL\Type\EmailType;



class EmailFieldType extends AbstractField
{
    public function getType(): String
    {
        return "email";
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

    public  function getGrapQlType(): ScalarType
    {
        return  new EmailType();
    }
    
}