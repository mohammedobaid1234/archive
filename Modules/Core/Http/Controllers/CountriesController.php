<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CountriesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "بيانات الدول";
    private $model = \Modules\Core\Entities\Country::class;

    public function manage(){
        \Auth::user()->authorize('core_module_countries_manage', 'view');

        $data['activePage'] = ['core' => 'countries'];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('core::countries', $data);
    }

    public function datatable(Request $request){
        \Auth::user()->authorize('core_module_countries_manage');

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
            ['title' => 'الحالة', 'column' => 'active_title'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        \Auth::user()->authorize('core_module_countries_store');

        $request->validate([
            'name' => 'required'
        ]);

        \DB::beginTransaction();
        try{
            $country = new $this->model;
            $country->name = trim($request->name);
            $country->created_by = \Auth::user()->id;
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
        \Auth::user()->authorize('core_module_countries_update');

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
