<?php

namespace Modules\BriskCore\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BriskRequest extends FormRequest{
    
    public function authorize(){
        return true;
    }
    
    public function rules(){
        $action = \Route::getCurrentRoute()->getActionName();
        $controller = explode('@', $action)[0];
        $model = (new $controller)->model;

        $rules = [];

        foreach((new $model)->getBriskFillable() as $column => $fillable){
            $column_rules = "";

            if(is_array($fillable)){
                if(isset($fillable['validation'])){
                    foreach($fillable['validation'] as $title => $rule){
                        if($column_rules !== ""){
                            $column_rules .= "|";
                        }

                        if(!is_numeric($title)){
                            $column_rules .= $title . ":" . $rule;
                        }else{
                            $column_rules .= $rule;
                        }
                    }
                }
            }

            $rules[$column] = $column_rules;
        }
        
        return $rules;
    }

    /**
     * TODO: customize error messages
     */
}
