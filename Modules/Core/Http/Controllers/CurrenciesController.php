<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CurrenciesController extends Controller{
    private $model = \Modules\Core\Entities\Currency::class;

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
                $response->where((trim($request->where_like_column) === "" ? 'full_name' : trim($request->where_like_column)), 'like', ('%' . trim($request->search) . '%'));
            }
        }

        if((int) trim($request->all)){
            return ["data" => $response->get()];
        }

        return $response->paginate(20);
    }
}
