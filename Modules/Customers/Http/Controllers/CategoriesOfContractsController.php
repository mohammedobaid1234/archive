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
    // public function index(Request $request){
    //     if(trim($request->search) == " " && trim($request->categories_of_contacts) == ""){
    //         return ["data" => []];
    //     }

    //     $response = $this->model::select('*');
    //     // $response->orderBy('created_at', 'DESC');

    //     if(is_numeric(trim($request->search))){
    //         $response->where('categories_of_contacts', trim($request->search));
    //     }elseif(trim($request->categories_of_contacts) !== ""){
    //         $response->where('categories_of_contacts', trim($request->categories_of_contacts));
    //     }else{
    //         $response->whereFullNameLike($request->search);
    //     }

    //     if($request->has('page')){
    //         return $response->paginate(20);
    //     }

    //     return ["data" => $response->get()];
    // }
    public function manage(){
        \Auth::user()->authorize('customers_module_categories_of_contracts_manage');

        $data['activePage'] = ['categories_of_contracts' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('customers::categories_of_contracts', $data);
    }

    public function datatable(Request $request){
        \Auth::user()->authorize('customers_module_categories_of_contracts_manage');

        $eloquent = $this->model::with([]);

        if((int) $request->filters_status){
            if(trim($request->name) !== ""){
                $eloquent->whereNameLike($request->name);
            }
        }

        $filters = [
            ['title' => 'الاسم', 'type' => 'input', 'name' => 'name'],
        ];

        $columns = [
            ['title' => 'الاسم', 'column' => 'name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        \Auth::user()->authorize('customers_module_categories_of_contracts_store');

        $request->validate([
            'name' => 'required'
        ]);

        \DB::beginTransaction();
        try{
            $country = new $this->model;
            $country->name = trim($request->name);
            $country->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }

    public function show($id){
        return $this->model::with([])->whereId($id)->first();
    }

    public function update(Request $request, $id){
        \Auth::user()->authorize('customers_module_categories_of_contracts_update');

        $request->validate([
            'name' => 'required'
        ]);

        \DB::beginTransaction();
        try{
            $country = $this->model::whereId($id)->first();
            $country->name = trim($request->name);
            $country->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
}
