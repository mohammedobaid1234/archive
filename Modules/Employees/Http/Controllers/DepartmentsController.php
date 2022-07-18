<?php

namespace Modules\Employees\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DepartmentsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " الأقسام";
    private $model = \Modules\Employees\Entities\Department::class;

    public function manage(){
        \Auth::user()->authorize('employees_module_departments_manage', 'view');

        $data['activePage'] = ['core' => 'departments'];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('employees::departments', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('employees_module_departments_manage');

        $eloquent = $this->model::with([]);

        if((int) $request->filters_status){
            if(trim($request->label) !== ""){
                $eloquent->whereLabelLike($request->label);
            }
        }

        $filters = [
            ['title' => 'الاسم', 'type' => 'input', 'name' => 'label'],
        ];

        $columns = [
            ['title' => 'الاسم', 'column' => 'label'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        \Auth::user()->authorize('employees_module_departments_store');

        $request->validate([
            'name' => 'required',
            'label' => 'required',

        ]);

        \DB::beginTransaction();
        try{
            $country = new $this->model;
            $country->name = trim($request->name);
            $country->label = trim($request->label);
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
        \Auth::user()->authorize('employees_module_departments_update');

        $request->validate([
            'name' => 'required',
            'label' => 'required',
        ]);

        \DB::beginTransaction();
        try{
            $country = $this->model::whereId($id)->first();
            $country->name = trim($request->name);
            $country->label = trim($request->label);
            $country->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }


}
