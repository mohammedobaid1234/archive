<?php 

namespace Modules\BriskCore\Traits\Resource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait CreateTrait {
    
    public function create(){
        return response()->json((new $this->model)->createFormGenerator((isset($this->except) ? $this->except : [])));  // return "input" => $rows 
    }
}