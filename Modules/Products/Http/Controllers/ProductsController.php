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
        
        $eloquent = $this->model::with(['category', 'created_by_user','currency']);
        
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
            if(trim($request->name) !== ""){
                $eloquent->WhereNameLike($request->name);
            }
            
        }

        $filters = [
            ['title' => 'رقم المنتج', 'type' => 'input', 'name' => 'id'],
            ['title' => 'اسم المنتج', 'type' => 'input', 'name' => 'name'],
            ['title' => 'العملة', 'type' => 'select', 'name' => 'currency_id', 'data' => ['options_source' => 'currencies', 'has_empty' => true]],
            ['title' => 'التصنيفات', 'type' => 'select', 'name' => 'category_id', 'data' => ['options_source' => 'categoriesOfProducts', 'has_empty' => true]]
        ];

        $columns = [
            ['title' => 'رقم المنتج', 'column' => 'id'],
            ['title' => 'الاسم', 'column' => 'name'],
            ['title' => 'التصنيف', 'column' => 'category.name'],
            ['title' => 'السعر', 'column' => 'price'],
            ['title' => 'العملة', 'column' => 'currency.name'],
            ['title' => 'الكمية المتوفرة', 'column' => 'quantity'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];
        
        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        $this->can('products_module_products_store');

        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'currency_id' => 'required',
            'quantity' => 'required',
            'category_id' => 'required',
        ]);

        if($this->model::where('name', trim($request->name))->count()){
            return response()->json(['message' => 'لا يمكن تكرار اسم الصنف.'], 403);
        }
        $currency =\Modules\Core\Entities\Currency::whereId($request->currency_id)->count();
        
        if($currency == 0 ){
            return response()->json(['message' => 'يرجى إضافة نوع العملة بشكل صحيح'], 403);
        }
        if($request->quantity == 0 ){
            return response()->json(['message' => 'لا يمكن إضافة صنف الكمية تساوي صفر'], 403);
        }
        $currency =\Modules\Products\Entities\Category::whereId($request->category_id)->count();

        if($request->category_id == 0 ){
            return response()->json(['message' => 'لا يمكن إضافة صنف الكمية تساوي صفر'], 403);
        }
        \DB::beginTransaction();
        try{
            $product = new $this->model;
            $product->name = trim($request->name);
            $product->price = trim($request->price);
            $product->currency_id = trim($request->currency_id);
            $product->quantity = trim($request->quantity);
            $product->category_id = trim($request->category_id);
            $product->created_by = \Auth::user()->id;
            $product->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok', 'category' => $product]);
    }
    public function show($id){
        return $this->model::with(['category', 'created_by_user','currency'])->whereId($id)->first();
    }

    public function update(Request $request, $product_id){
        $this->can('products_module_products_update');

        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'currency_id' => 'required',
            'quantity' => 'required',
            'category_id' => 'required',
        ]);

        if($this->model::where('id','<>',$product_id)->where('name', trim($request->name))->count()){
            return response()->json(['message' => 'لا يمكن تكرار اسم الصنف.'], 403);
        }
        $currency =\Modules\Core\Entities\Currency::whereId($request->currency_id)->count();
        
        if($currency == 0 ){
            return response()->json(['message' => 'يرجى إضافة نوع العملة بشكل صحيح'], 403);
        }
        if($request->quantity == 0 ){
            return response()->json(['message' => 'لا يمكن إضافة صنف الكمية تساوي صفر'], 403);
        }
        $currency =\Modules\Products\Entities\Category::whereId($request->category_id)->count();

        if($request->category_id == 0 ){
            return response()->json(['message' => 'لا يمكن إضافة صنف الكمية تساوي صفر'], 403);
        }
        \DB::beginTransaction();
        try{
            $product =  $this->model::whereId($product_id)->first();
            $product->name = trim($request->name);
            $product->price = trim($request->price);
            $product->currency_id = trim($request->currency_id);
            $product->quantity = trim($request->quantity);
            $product->category_id = trim($request->category_id);
            $product->created_by = \Auth::user()->id;
            $product->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok', 'category' => $product]);
    }
}
