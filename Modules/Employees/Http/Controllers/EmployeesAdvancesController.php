<?php

namespace Modules\Employees\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmployeesAdvancesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " سلف الموظفين";
    private $model = \Modules\Employees\Entities\EmployeeAdvance::class;

    public function manage(){
        \Auth::user()->authorize('employees_module_employees_advances_manage', 'view');

        $data['activePage'] = ['employees_advances' => 'employees_advances'];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('employees::employees_advances', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('employees_module_employees_advances_manage');

        $eloquent = $this->model::with(['employee']);

        if((int) $request->filters_status){
            if (trim($request->employee_id) !== '') {
                $eloquent->whereHas('employee', function($query) use ($request){
                    $query->where('id',trim($request->employee_id));
                });
            }
            
        }

        $filters = [
            ['title' => 'اسم الموظف', 'type' => 'select', 'name' => 'employee_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
            ['title' =>  '  تاريخ  الإنشتاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],

        ];

        $columns = [
            ['title' => 'اسم الموظف ', 'column' => 'employee.full_name'],
            ['title' =>  ' القيمة', 'column' => 'amount'],
            ['title' =>  ' تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' =>  'بواسطة', 'column' => ''],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    
    public function store(Request $request){
        \Auth::user()->authorize('employees_module_employees_advances_store');

        $request->validate([
            'amount' => 'required',
            'employee_id' => 'required',
        ]);
        \DB::beginTransaction();
        try{
            $employee_advance = new $this->model;
            $employee_advance->employee_id = trim($request->employee_id);
            $employee_advance->amount = trim($request->amount);
            $employee_advance->created_by = \Auth::user()->id;
            $employee_advance->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function show($id){
        return $this->model::with(['employee'])->whereId($id)->first();
    }
    public function update(Request $request, $id){
        \Auth::user()->authorize('employees_module_employees_advances_update');

        $request->validate([
            'amount' => 'required',
            'employee_id' => 'required',
        ]);
        \DB::beginTransaction();
        try{
            $employee_advance =  $this->model::whereId($id)->first();
            $employee_advance->employee_id = trim($request->employee_id);
            $employee_advance->amount = trim($request->amount);
            $employee_advance->created_by = \Auth::user()->id;
            $employee_advance->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
}
