<?php 

namespace Modules\BriskCore\Traits\Resource;

use Illuminate\Http\Request;

trait IndexTrait {

    public function index(Request $request){
        $response = $this->model::select('*');
        
        if(in_array('scopeActive', get_class_methods($this->model))){
            $response->active();
        }

        $response->orderBy('created_at', 'DESC');

        if($request->has('search') && trim($request->search)){
            if(is_numeric(trim($request->search))){
                $response->where('id', trim($request->search));     
            }else{
                $response->where((trim($request->where_like_column) === "" ? 'name' : trim($request->where_like_column)), 'like', ('%' . trim($request->search) . '%'));     
            }
        }
      
        if((int) trim($request->all)){
            return ["data" => $response->get()];
        }
      
        return $response->paginate(20);
    }
}