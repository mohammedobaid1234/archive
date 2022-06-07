<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoriesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "إدارة التصنيفات";
    private $model = \Modules\Products\Entities\Category::class;

    public function index(Request $request){
        $this->can('products_module_categories_manage');

        $response = $this->model::with([]);


        if($request->has('search') && trim($request->search)){
            if(is_numeric(trim($request->search))){
                $response->where('id', trim($request->search));
            }else{
                $response->whereNameLike($request->search);
            }
        }

        return $response->paginate(10);
    }

    public function manage() {
        $this->can('products_module_categories_manage', "view");

        $data['activePage'] = ['categories' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('products::categories', $data);
    }

    public function datatable(Request $request){
        \Auth::user()->authorize('products_module_categories_manage');

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
        $this->can('products_module_categories_store');

        $request->validate([
            'name' => 'required'
        ]);

        if($this->model::where('name', trim($request->name))->count()){
            return response()->json(['message' => 'لا يمكن تكرار اسم التصنيف.'], 403);
        }

        \DB::beginTransaction();
        try{
            $category = new $this->model;
            $category->name = trim($request->name);
            $category->created_by = \Auth::user()->id;
            $category->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok', 'category' => $category]);
    }
    public function show($id){
        return $this->model::whereId($id)->first();
    }
}
