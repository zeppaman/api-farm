<?php

namespace App\Services;

interface ICrudService 
{
    function get(String $db,String $collection,String $id);

    function update(String $db,String $collection, $data,bool $replace=false);

    function add(String $db,String $collection, $data);

    function delete(String $db, String $collection,String $id);

    function find(String $db,String $collection,$query=[],$skip=0, $limit=1000, $sort=array());
}