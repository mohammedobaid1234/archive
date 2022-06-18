<?php

namespace Modules\Employees\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class JawwalBillsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = " فواتير جوال";
    private $model = \Modules\Employees\Entities\JawwalBill::class;

    public function manage(){
        \Auth::user()->authorize('employees_module_jawwal_bill_manage', 'view');

        $data['activePage'] = ['jawwal_bill' => 'jawwal_bill'];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('employees::jawwal_bill', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('employees_module_jawwal_bill_manage');

        $eloquent = $this->model::with(['employee']);

        if((int) $request->filters_status){
            if (trim($request->employee_id) !== '') {
                $eloquent->whereHas('employee', function($query) use ($request){
                    $query->where('id',trim($request->employee_id));
                });
            }
            if (trim($request->mobile_no) !== '') {
                $eloquent->where('mobile_no', "LIKE" , "%". trim($request->mobile_no) . "%");

            }
        }

        $filters = [
            ['title' => 'اسم الموظف', 'type' => 'select', 'name' => 'employee_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
            ['title' => 'رقم الفاتورة ', 'type' => 'input', 'name' => 'mobile_no'],
            ['title' =>  '  تاريخ تفعيل الفاتورة', 'type' => 'input', 'name' => 'activate_date', 'date_range' => true],


        ];

        $columns = [
            ['title' => 'رقم الفاتورة', 'column' => 'mobile_no'],
            ['title' => 'قيمة الفاتورة', 'column' => 'value'],
            ['title' => 'اسم الموظف حامل الفاتورة', 'column' => 'employee.full_name'],
            ['title' =>  '  تاريخ تفعيل الفاتورة', 'column' => 'activate_date'],
            ['title' =>  '  تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    
    public function store(Request $request){
        \Auth::user()->authorize('employees_module_departments_store');

        $request->validate([
            'mobile_no' => 'required',
            'value' => 'required',
            'employee_id' => 'required',
            'activate_date' => 'required',
            
        ]);
        if (trim($request->mobile_no) !== "") {
            if (strlen(trim($request->mobile_no)) !== 10) {
                return response()->json(['message' => "يرجى التحقق من صحة رقم الجوال."], 403);
            }
    
            if (\Modules\Employees\Entities\JawwalBill::where('mobile_no', trim($request->mobile_no))->count()) {
                return response()->json(['message' => "لا يمكن تكرار رقم الجوال"], 403);
            }
        }
        \DB::beginTransaction();
        try{
            $JawwalBill = new $this->model;
            $JawwalBill->mobile_no = trim($request->mobile_no);
            $JawwalBill->value = trim($request->value);
            $JawwalBill->employee_id = trim($request->employee_id);
            $JawwalBill->activate_date = trim($request->activate_date);
            $JawwalBill->save();

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
        \Auth::user()->authorize('employees_module_departments_update');

        $request->validate([
            'mobile_no' => 'required',
            'value' => 'required',
            'employee_id' => 'required',

        ]);
        if (trim($request->mobile_no) !== "") {
            if (strlen(trim($request->mobile_no)) !== 10) {
                return response()->json(['message' => "يرجى التحقق من صحة رقم الجوال."], 403);
            }
    
            if (\Modules\Employees\Entities\JawwalBill::where('id', '<>', $id)->where('mobile_no', trim($request->mobile_no))->count()) {
                return response()->json(['message' => "لا يمكن تكرار رقم الجوال"], 403);
            }
        }
        \DB::beginTransaction();
        try{
            $JawwalBill =  $this->model::whereId($id)->first();
            $JawwalBill->mobile_no = trim($request->mobile_no);
            $JawwalBill->value = trim($request->value);
            $JawwalBill->employee_id = trim($request->employee_id);
            $JawwalBill->save();

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
}
