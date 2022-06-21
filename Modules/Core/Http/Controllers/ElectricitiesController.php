<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ElectricitiesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات سحب الكهرباء";
    private $model = \Modules\Core\Entities\Electricity::class;


    public function manage(){
        \Auth::user()->authorize('core_module_electricities_manage');

        $data['activePage'] = ['electricities' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('core::electricities', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('core_module_electricitiess_manage');

        $eloquent = $this->model::with(['created_by_user','employee']);

        if ((int) $request->filters_status) {
          
            if (trim($request->employee_id) !== '') {
                $eloquent->whereHas('employee', function($query) use ($request){
                    $query->where('id', trim($request->employee_id));
                });
            }
            
            if (trim($request->created_by) !== "") {
                $eloquent->whereHas('created_by_user', function($query) use ($request){
                    $query->where('name', "LIKE" , "%". trim($request->created_by_user) . "%");
                });
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
            if (trim($request->type) !== "") {
                $eloquent->where('type',$request->type);
            }
        }

        $filters = [
            ['title' => 'اسم الموظف المسؤول', 'type' => 'select', 'name' => 'employee_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
            ['title' => 'نوع الخط', 'type' => 'select', 'name' => 'type', 'data' => ['options_source' => 'electronic_type', 'has_empty' => true]],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'اسم الموظف المسؤول ', 'column' => 'employee.full_name'],
            ['title' => 'قيمة  القراءة السابقة ', 'column' => 'previous_reading'],
            ['title' => 'قيمة القراءة الحالية ', 'column' => 'current_reading'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        \Auth::user()->authorize('core_module_electricities_store');

        $request->validate([
            'employee_id' => 'required',
            'previous_reading' => 'required',
            'current_reading' => 'required',
            'type' => 'required',
        ]);

        \DB::beginTransaction();
        try {
            $electricities = new \Modules\Core\Entities\Electricity;
            $electricities->employee_id = $request->employee_id;
            $electrony = \Modules\Core\Entities\Electricity::where('type', $request->type)->last();
            if($electrony){
                $electricities->current_reading = $electrony->previous_reading;
            }else{
                $electricities->previous_reading = $request->previous_reading;
            }
            $electricities->current_reading = $request->current_reading;
            $electricities->type = $request->type;
            $electricities->created_by = \Auth::user()->id;
            $electricities->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function create(){
        return [
            "title" => "اضافة إيصال جديد",
            "inputs" => [
                ['title' => 'اسم الموظف المسؤول  ', 'input' => 'select', 'name' => 'employee_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'employees', 'placeholder' => 'اسم الموظفين...'],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee_id']]],
                [
                    ['title' => 'قيمة القراءة السابقة  ', 'input' => 'input', 'name' => 'previous_reading','classes' => ['select2'],'operations' => ['show' => ['text' => 'previous_reading']]],
                    ['title' => 'قيمة القراءة الحالية ', 'input' => 'input', 'name' => 'current_reading',  'required' => true, 'classes' => ['numeric'],'required' => true,'operations' => ['show' => ['text' => 'current_reading']]],
                ],
                ['title' => 'النوع ', 'input' => 'select', 'name' => 'type', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'electronic_type'],"rowIndex" => 1,'operations' => ['show' => ['text' => 'type']]],
            ]
        ];
    }
    public function show($id){
        return $this->model::with(['created_by_user','employee'])->whereId($id)->first();
    }
    public function update(Request $request, $id){
        \Auth::user()->authorize('core_module_electricities_update');

        $request->validate([
            'employee_id' => 'required',
            'previous_reading' => 'required',
            'current_reading' => 'required',
            'type' => 'required',
        ]);

        \DB::beginTransaction();
        try {
            $electricities =  \Modules\Core\Entities\Electricity::whereId($id)->first();
            $electricities->employee_id = $request->employee_id;
            $electricities->previous_reading = $request->previous_reading;
            $electricities->current_reading = $request->current_reading;
            $electricities->type = $request->type;
            $electricities->created_by = \Auth::user()->id;
            $electricities->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
}
