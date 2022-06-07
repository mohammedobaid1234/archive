<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BanksController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "بيانات البنوك";
    private $model = \Modules\Core\Entities\Bank::class;

    public function index(Request $request){
        $response = $this->model::select('*');

        $response->orderBy('created_at', 'DESC');

        if((int) trim($request->all)){
            return ["data" => $response->get()];
        }
        
        return $response->paginate(20);
    }

    public function manage(){
        \Auth::user()->authorize('core_module_banks_manage', 'view');

        $data['activePage'] = ['core' => 'banks'];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('core::banks', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('core_module_banks_manage');

        $eloquent = $this->model::with([]);

        if((int) $request->filters_status){
            if(trim($request->name) !== ""){
                $eloquent->whereNameLike($request->name);
            }
            if(trim($request->address) !== ""){
                $eloquent->whereAddressLike($request->address);
            }
            if(trim($request->mobile_number) !== ""){
                $eloquent->whereMobileNumberLike($request->mobile_number);
            }
        }

        $filters = [
            ['title' => 'الاسم', 'type' => 'input', 'name' => 'name'],
            ['title' => 'العنوان', 'type' => 'input', 'name' => 'address'],
            ['title' => 'رقم الهاتف', 'type' => 'input', 'name' => 'mobile_number'],
        ];

        $columns = [
            ['title' => 'الاسم', 'column' => 'name'],
            ['title' => 'العنوان', 'column' => 'address'],
            ['title' => 'رقم الهاتف', 'column' => 'mobile_number'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function store(Request $request){
        \Auth::user()->authorize('core_module_banks_store');

        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'mobile_number' => 'required',
        ]);

        \DB::beginTransaction();
        try{
            $bank = new $this->model;
            $bank->name = trim($request->name);
            $bank->address = trim($request->address);
            $bank->mobile_number = trim($request->mobile_number);
            $bank->save();

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
        \Auth::user()->authorize('core_module_banks_update');

        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'mobile_number' => 'required',
        ]);

        \DB::beginTransaction();
        try{
            $bank = $this->model::whereId($id)->first();
            $bank->name = trim($request->name);
            $bank->address = trim($request->address);
            $bank->mobile_number = trim($request->mobile_number);
            $bank->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    
}
