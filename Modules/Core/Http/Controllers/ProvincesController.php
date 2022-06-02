<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProvincesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "بيانات المحافظات";
    private $model = \Modules\Core\Entities\CountryProvince::class;

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
    
    public function manage($country_id){
        \Auth::user()->authorize('core_module_country_provinces_manage', 'view');

        $data['activePage'] = ['core' => 'countries'];
        $data['breadcrumb'] = [
            ['title' => 'بيانات الدول', 'url' => 'countries/manage'],
            ['title' => \Modules\Core\Entities\Country::whereId($country_id)->first()->name],
            ['title' => $this->title],
        ];

        $data['country_id'] = $country_id;

        return view('core::provinces', $data);
    }

    public function datatable(Request $request, $country_id){
        \Auth::user()->authorize('core_module_country_provinces_manage');

        $eloquent = $this->model::with([]);

        $eloquent->where('country_id', $country_id);

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
    
    public function store(Request $request, $country_id){
        \Auth::user()->authorize('core_module_country_provinces_store');

        $request->validate([
            'name' => 'required'
        ]);
        
        \DB::beginTransaction();
        try{
            $province = new $this->model;
            $province->country_id = $country_id;
            $province->name = trim($request->name);
            $province->created_by = \Auth::user()->id;
            $province->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    
    public function show($country_id, $id){
        return $this->model::with([])->whereId($id)->first();
    }
    
    public function update(Request $request, $country_id, $id){
        \Auth::user()->authorize('core_module_country_provinces_update');

        $request->validate([
            'name' => 'required'
        ]);
        
        \DB::beginTransaction();
        try{
            $province = $this->model::whereId($id)->first();
            $province->name = trim($request->name);
            $province->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
}