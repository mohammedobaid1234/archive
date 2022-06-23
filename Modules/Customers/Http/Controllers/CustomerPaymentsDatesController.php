<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomerPaymentsDatesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات دفعات الزبائن";
    private $model = \Modules\Customers\Entities\CustomerPaymentsDate::class;

    public function manage(){
        \Auth::user()->authorize('customers_module_customer_payments_dates_manage');

        $data['activePage'] = ['customer_payments_dates' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('customers::customer_payments_dates', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('customers_module_customer_payments_dates_manage');

        $eloquent = $this->model::with(['currency', 'created_by_user', 'employee','payment','payment.customer']);

        if ((int) $request->filters_status) {
        
            if (trim($request->customer_id) !== '') {
                $eloquent->where('customer_id',$request->customer_id);
            }
            if (trim($request->employee_id) !== '') {
                $eloquent->where('employee_id',$request->employee_id);
            }
            if (trim($request->mobile_no) !== '') {
                $eloquent->whereHas('customer', function($query) use ($request){
                    $query->where('mobile_no', "LIKE" , "%". trim($request->mobile_no) . "%");
                });
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
            if (trim($request->due_date) !== '') {
                $eloquent->WhereDueDate($request->due_date);
            }
        }

        $filters = [
            ['title' => 'رقم الملف', 'type' => 'input', 'name' => 'contract_number'],
            ['title' => 'اسم العميل', 'type' => 'select', 'name' => 'customer_id', 'data' => ['options_source' => 'customers', 'has_empty' => true]],
            ['title' => 'اسم الموظف', 'type' => 'select', 'name' => 'employee_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
            ['title' =>  '  تاريخ الإستحقاق', 'type' => 'input', 'name' => 'due_date', 'date_range' => true],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'رقم العقد', 'column' => 'id'],
            ['title' => 'الاسم العميل', 'column' => 'payment.customer.full_name'],
            ['title' => 'اسم الموظف المكلف', 'column' => 'employee.full_name'],
            ['title' => 'نوع الدفعة', 'column' => 'label'],
            ['title' => ' الحالة  ', 'column' => 'state' , 'formatter' => 'states' ],
            ['title' => ' قيمة  ', 'column' => 'amount'],
            ['title' => ' نوع العملة', 'column' => 'currency.name'],
            ['title' => ' تاريخ الإستحقاق ', 'column' => 'due_date'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function show($id){
        return $this->model::with(['currency', 'created_by_user', 'employee','payment','payment.customer'])->whereId($id)->first();
    }
    public function stateChange($id){
       $cpd=  $this->model::whereId($id)->first();
       if($cpd->state == 'تم_السداد'){
        $cpd->state = 'لم_يتم_السداد';
        $cpd->save();
        }else if($cpd->state == 'لم_يتم_السداد'){
                $cpd->state = 'تم_السداد';
                $cpd->save();
            }
       return response()->json(['message' => 'ok']);

    }

}
