<?php 

namespace Modules\BriskCore\Traits;

use Illuminate\Http\Request;

trait ModelTrait {
    
    public function createFormGenerator($except){
        $rows = [];

        foreach ($this->getEntityAttributesMethods() as $key => $attributeMethod) {
            //check if except contain in class bt get att "name"
            if(in_array(trim($this->$attributeMethod()["name"]), $except)){
                continue;
            }

            // if(!isset($this->$attributeMethod()["rowIndex"])){
            //     continue;
            // }

            // if(!isset($rows[$this->$attributeMethod()["rowIndex"]])){
            //     $rows[$this->$attributeMethod()["rowIndex"]] = [];
            // }

            // array_push($rows[$this->$attributeMethod()["rowIndex"]], $this->$attributeMethod());
            // if not except method store for example 1=>'name of method" 1=> $key
            if(!isset($rows[$key])){
                $rows[$key] = [];
            }
            // make nested array => [[1=>_name, 2=>'email']] why?? when not exisit any method create return [] empty array 
            $rows[$key][] = $this->$attributeMethod();
        }

        $response = [];

        foreach($rows as $row){
            if(sizeof($row) > 1){
                $response[] = $row;
            }else{
                $response[] = $row[0];
            }
        }

        return ["inputs" => $response];
    }

    public function getEntityAttributesMethods(){
   
        $attributes = [];
        // get method of parent class that (class of method $this (getEntityAttributesMethods))
        foreach (get_class_methods(get_class($this)) as $key => $method) {
            if($method[0] == "_" && $method[1] !== "_"){
                // first letter "_" && second letter not "_" 
                // array_push($attributes, substr($method, 1, strlen($method)));
                array_push($attributes, $method);
            }
        }
    
        return $attributes;
    }

    public function getBriskFillable(){
        return $this->fillableBrisk;
    }
}