<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoriesOfContractsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "أنوع العقود";
    private $model = \Modules\Customers\Entities\CategoriesOfContracts::class;
    public function index(Request $request){
        if(trim($request->search) == " " && trim($request->categories_of_contacts) == ""){
            return ["data" => []];
        }

        $response = $this->model::select('*');
        // $response->orderBy('created_at', 'DESC');

        if(is_numeric(trim($request->search))){
            $response->where('categories_of_contacts', trim($request->search));
        }elseif(trim($request->categories_of_contacts) !== ""){
            $response->where('categories_of_contacts', trim($request->categories_of_contacts));
        }else{
            $response->whereFullNameLike($request->search);
        }

        if($request->has('page')){
            return $response->paginate(20);
        }

        return ["data" => $response->get()];
    }
}
