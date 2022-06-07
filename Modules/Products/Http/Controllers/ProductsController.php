<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "إدارة المنتجات";
    private $model = \Modules\Products\Entities\Product::class;

    public function index(Request $request){
        $response = $this->model::select('*');
        

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
    public function manage(){
        \Auth::user()->authorize('products_module_products_manage', 'view');

        $data['activePage'] = ['products' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('products::products', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('products_module_products_manage');

        $eloquent = $this->model::with(['category', 'customer', 'province', 'approved_by_user']);

        if ((int) $request->filters_status) {
            if (trim($request->id) !== "") {
                $eloquent->where('id', trim($request->id));
            }
            if(trim($request->currency_id) !== ""){
                $eloquent->where('currency_id', trim($request->currency_id));
            }
            if(trim($request->category_id) !== ""){
                $eloquent->where('category_id', trim($request->category_id));
            }
          
        }

        $filters = [
            ['title' => 'رقم المنتج', 'type' => 'input', 'name' => 'id'],
            ['title' => 'العملة', 'type' => 'select', 'name' => 'currency_id', 'data' => ['options_source' => 'currencies', 'has_empty' => true]],
            ['title' => 'التصنيفات', 'type' => 'select', 'name' => 'category_id', 'data' => ['options_source' => 'categoriesOfProducts', 'has_empty' => true]]
        ];

        $columns = [
            ['title' => 'رقم المنتج', 'column' => 'id'],
            ['title' => 'الاسم', 'column' => 'name'],
            ['title' => 'التصنيف', 'column' => 'category.name'],
            ['title' => 'السعر', 'column' => 'price'],
            ['title' => 'العملة', 'column' => 'currency_id'],
            ['title' => 'الكمية المتوفرة', 'column' => 'quantity'],
            ['title' => 'بواسطة', 'column' => 'approved_by_user.name'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'الحالة', 'column' => 'active_title'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
}
