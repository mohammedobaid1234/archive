<?php 

namespace Modules\BriskCore\Traits;

use Illuminate\Http\Request;

trait FormRequestTrait {

    public function can($permission, $type = "json"){
        $type = strtolower($type);

        if(!\Auth::user()->can($permission)){
            if($type == "view"){
                response(view('errors.401'), 403)->send();
                die();
            }
            
            if($type == "json"){
                response()->json(['message' => "لا تملك صلاحية الوصول إلى هذا الإجراء."], 403)->send();
                die();
            }
            
            if($type == "boolean"){
                return false;
            }
        }
            
        if($type == "boolean"){
            return true;
        }
    }
}