<?php

namespace Apifarm\CoreBundle\Controller;

use Apifarm\CoreBundle\Services\CrudService;
use Apifarm\CoreBundle\Services\ICrudService;
use Apifarm\CoreBundle\Services\TypeService;
use Doctrine\ORM\Query\TreeWalker;
use MongoDB\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;


class GraphQLController extends AbstractController
{

    protected $service;
    protected $typeService;


    public function __construct(ICrudService $service, TypeService $typeService)
    {
      $this->service=$service;
      $this->typeService=$typeService;
    }

    /**
     * @Route("/api/graph/{database}", name="graphql_test", methods={"GET"})
     */
    public function query($database)
    {
        $schema= $this->service->getSchema($database);
      
        $objects=[];

        $config=[
            'name' => 'Query',
            'fields' => array(),
            "resolveField"=> function($objectValue, $args, $context, ResolveInfo $info)
            {           
              
                $db=$context["db"];

                $query=array();
             
                foreach($args as $key=>$value)
                {
                   
                    if(!empty($value))
                    {
                        $query[$key]=$value;
                    }
                }    

                $limit=$args["limit"];
                $skip=$args["skip"];
                unset($query["limit"]);
                unset($query["skip"]);           

               

                $result= $this->service->find($db,$info->fieldName,$query, $skip,$limit);                       
            
                return $result;
            }
        ];

        foreach($schema as $entity)
        {
           
            $entityType = [
                'name' => $entity->name,
                'description' => "Desc for $entity->name",               
                'resolveField' => function($item, $args, $context, ResolveInfo $info) {                
              
                  return $item->{$info->fieldName};                 

                }
            ];

           
            $entityType["fields"]["_id"]=array(
                'type' => Type::string(),
                'description' => "filter by id",
                'defaultValue' => null                            
            );   

            if($entity->fields)
            {
                foreach($entity->fields as $name=>$value)
                {                  
                    if($value->type)
                    {
                        $type=$this->typeService->getTypeDefinition($value["type"]);

                        $entityType["fields"][$name]=array(
                                'type' => $type->getGrapQlType(),
                                'description' => $value->label                            
                        );   
                    }                 
                }             
               
            }         
        
          
            $config["fields"][$entity->name] = [
                'type' => Type::listOf(new ObjectType($entityType)),
                'description' => "Entity $entity->name",
                'args' => [
                    "_id" => [
                    'type' => Type::string(),
                    'description' => 'filter by id',
                    'defaultValue' => null
                    ],
                    "limit" => [
                        'type' => Type::int(),
                        'description' => 'filter by id',
                        'defaultValue' => 1000
                        ]
                        ,
                    "skip" => [
                        'type' => Type::int(),
                        'description' => 'filter by id',
                        'defaultValue' => 0
                    ],
                    
                ],                
                
            ];
        
        
            $config[$entity->name]["fields"]["_id"]=array(
                'type' => Type::string(),
                'description' => "filter by id",
                'defaultValue' => null                           
            );   

            if($entity->fields)
            {
                foreach($entity->fields as $name=>$value)
                {
                    
                    if($value->type)
                    {
                        $type=$this->typeService->getTypeDefinition($value["type"]);

                        $config[$entity->name]["fields"][$name]=array(
                                'type' => $type->getGrapQlType(),
                                'description' => "filter by $value->label",
                                'defaultValue' => null                            
                        );   
                    }                 
                }             
               
            }
           
       

        }
       
      

        
        $schema = new Schema([
            'query' => new ObjectType($config)
        ]);

        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);
        $query = $input['query'];
        $variableValues = isset($input['variables']) ? $input['variables'] : null;
        
        $variableValues["db"]=$database;

        try {
            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQL::executeQuery($schema, $query, $variableValues,  $variableValues, $variableValues);
           return new JsonResponse( $result->toArray());
        } catch (\Exception $e) {
            $output = [
                'errors' => [
                    [
                        'message' => $e->getMessage()
                    ]
                ]
            ];
        }
         return new JsonResponse($output);
    }
}



