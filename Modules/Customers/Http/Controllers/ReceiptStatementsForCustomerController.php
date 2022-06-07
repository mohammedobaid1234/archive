<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReceiptStatementsForCustomerController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات إيصالات القبض";
    private $model = \Modules\Customers\Entities\ReceiptStatement::class;
    public function datatable(Request $request, $customer_id){
        \Auth::user()->authorize('customers_module_receipt_statements_manage');

        $eloquent = $this->model::with(['customer', 'created_by_user','employee', 'currency', 'bank'])->where('customer_id', $customer_id);

        if ((int) $request->filters_status) {
            if (trim($request->customer_id) !== '') {
                $eloquent->whereHas('customer', function($query) use ($request){
                    $query->where('id', trim($request->customer_id));
                });
            }
            if (trim($request->bank_id) !== '') {
                $eloquent->whereHas('bank', function($query) use ($request){
                    $query->where('id', trim($request->bank_id));
                });
            }
            if (trim($request->mobile_no) !== '') {
                $eloquent->whereHas('customer', function($query) use ($request){
                    $query->where('mobile_no', "LIKE" , "%". trim($request->mobile_no) . "%");
                });
            }
            if (trim($request->employee_id) !== '') {
                $eloquent->whereHas('employee', function($query) use ($request){
                    $query->where('id', trim($request->employee_id));
                });
            }
            if (trim($request->transaction_number) !== "") {
                $eloquent->where('transaction_number', 'LIKE', "%".trim($request->transaction_number).'%');
            }
            if (trim($request->transaction_number) !== "") {
                $eloquent->where('transaction_number', 'LIKE', "%".trim($request->transaction_number).'%');
            }
            if (trim($request->transaction_date) !== '') {
                $eloquent->WhereTransactionDate($request->transaction_date);
            }
            if (trim($request->transaction_type) !== "") {
                $eloquent->where('transaction_type', 'LIKE', "%".trim($request->transaction_type).'%');
            }
            if (trim($request->payment_method) !== "") {
                $eloquent->where('payment_method', 'LIKE', "%".trim($request->payment_method).'%');
            }
            if (trim($request->check_number) !== "") {
                $eloquent->where('check_number', 'LIKE', "%".trim($request->check_number).'%');
            }
            if (trim($request->check_due_date) !== '') {
                $eloquent->WhereCheckDueDate($request->check_due_date);
            }
            if (trim($request->next_due_date) !== '') {
                $eloquent->WhereNextDueDate($request->next_due_date);
            }
            if (trim($request->opposite) !== "") {
                $eloquent->where('opposite', 'LIKE', "%".trim($request->opposite).'%');
            }
            if (trim($request->other_terms) !== "") {
                $eloquent->where('other_terms', 'LIKE', "%".trim($request->other_terms).'%');
            }
            if (trim($request->created_by) !== "") {
                $eloquent->whereHas('created_by_user', function($query) use ($request){
                    $query->where('name', "LIKE" , "%". trim($request->created_by_user) . "%");
                });
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
          
        }

        $filters = [
            ['title' => 'رقم الإيصال', 'type' => 'input', 'name' => 'transaction_number'],
            ['title' => 'اسم العميل', 'type' => 'select', 'name' => 'customer_id', 'data' => ['options_source' => 'customers', 'has_empty' => true]],
            ['title' => 'رقم جوال العميل', 'type' => 'input', 'name' => 'mobile_no'],
            ['title' => 'اسم الموظف المسؤول', 'type' => 'select', 'name' => 'employee_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
            ['title' => ' رقم الشيك', 'type' => 'input', 'name' => 'check_number'],
            ['title' => ' اسم البنك لصرف الشيك', 'type' => 'select', 'name' => 'bank_id', 'data' => ['options_source' => 'banks', 'has_empty' => true]],
            ['title' =>  ' تاريخ إستحقاق باقي الرصيد', 'type' => 'input', 'name' => 'next_due_date', 'date_range' => true],
            ['title' =>  ' تاريخ إستحقاق الشيك', 'type' => 'input', 'name' => 'check_due_date', 'date_range' => true],
            ['title' =>  ' تاريخ المعاملة', 'type' => 'input', 'name' => 'transaction_date', 'date_range' => true],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'رقم الإيصال', 'column' => 'transaction_number'],
            ['title' => 'الاسم العميل', 'column' => 'customer.full_name'],
            ['title' => 'اسم الموظف المكلف', 'column' => 'employee.full_name'],
            ['title' => 'المبلع الأساسي', 'column' => 'basic_amount'],
            ['title' => 'المبلع المستحق', 'column' => 'received_amount'],
            ['title' => 'المبلع المتبقي', 'column' => 'remaining_amount'],
            ['title' => 'نوع العملة', 'column' => 'currency.name'],
            ['title' => 'نوع المعاملة', 'column' => 'transaction_type'],
            ['title' => 'تاريخ المعاملة', 'column' => 'transaction_date'],
            ['title' => 'طريقة الدفع', 'column' => 'payment_method'],
            ['title' => 'رقم الشيك', 'column' => 'check_number'],
            ['title' => 'اسم البنك لصرف الشيك', 'column' => 'bank.name'],
            ['title' => ' تاريخ صرف الشيك', 'column' => 'check_due_date'],
            ['title' => ' تاريخ الاستحقاق للصرف ', 'column' => 'next_due_date',],
            ['title' => "مقابل", 'column' => 'opposite','formatter' => 'opposite'],
            ['title' => "شروط أخرى", 'column' => 'other_terms','formatter' => 'other_terms'],
            ['title' => "صورة الإيصال", 'column' => 'image','formatter' => 'image'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
}
