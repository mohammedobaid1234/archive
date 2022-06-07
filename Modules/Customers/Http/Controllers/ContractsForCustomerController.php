<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ContractsForCustomerController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات العقود";
    private $model = \Modules\Customers\Entities\contract::class;

    public function datatable(Request $request, $customer_id){
        \Auth::user()->authorize('customers_module_contracts_for_customer_manage');

        $eloquent = $this->model::with(['customer', 'created_by_user', 'category_of_contract', 'note','product_for_user'])->whereId($customer_id);

        if ((int) $request->filters_status) {
            if (trim($request->id) !== "") {
                $eloquent->where('id', trim($request->id));
            }
            if (trim($request->category_of_contract) !== "") {
                $eloquent->where('category_of_contract', trim($request->category_of_contract));
            }
            if (trim($request->customer_id) !== '') {
                $eloquent->where('customer_id',$request->customer_id);
            }
            if (trim($request->mobile_no) !== '') {
                $eloquent->whereHas('customer', function($query) use ($request){
                    $query->where('mobile_no', "LIKE" , "%". trim($request->mobile_no) . "%");
                });
            }
            if (trim($request->product_type) !== "") {
                $eloquent->whereHas('product_for_user', function($query) use ($request){
                    $query->where('product_type', 'LIKE', "%".trim($request->product_type).'%');
                });
            }
            if (trim($request->product_model) !== "") {
                $eloquent->whereHas('product_for_user', function($query) use ($request){
                    $query->where('product_model', 'LIKE', "%".trim($request->product_model).'%');
                });
            }
            if (trim($request->product_capacity) !== "") {
                $eloquent->whereHas('product_for_user', function($query) use ($request){
                    $query->where('product_capacity', 'LIKE', "%".trim($request->product_capacity).'%');
                });
            }
            if (trim($request->contract_starting_date) !== '') {
                $eloquent->WhereStaredAt($request->contract_starting_date);
            }
            if (trim($request->contract_ending_date) !== '') {
                $eloquent->WhereEndedAt($request->contract_ending_date);
            }
            if (trim($request->product_type) !== "") {
                $eloquent->whereHas('product_for_user', function($query) use ($request){
                    $query->where('product_type', 'LIKE', "%".trim($request->product_type).'%');
                });
            }
            if (trim($request->product_model) !== "") {
                $eloquent->whereHas('product_for_user', function($query) use ($request){
                    $query->where('product_model', 'LIKE', "%".trim($request->product_model).'%');
                });
            }
            if (trim($request->product_capacity) !== "") {
                $eloquent->whereHas('product_for_user', function($query) use ($request){
                    $query->where('product_capacity', 'LIKE', "%".trim($request->product_capacity).'%');
                });
            }
            if (trim($request->contract_starting_date) !== '') {
                $eloquent->WhereStaredAt($request->contract_starting_date);
            }
            if (trim($request->contract_ending_date) !== '') {
                $eloquent->WhereEndedAt($request->contract_ending_date);
            }
            if (trim($request->created_at) !== '') {
                $eloquent->WhereCreatedAt($request->created_at);
            }
        }

        $filters = [
            ['title' => 'رقم الملف', 'type' => 'input', 'name' => 'id'],
            ['title' => 'اسم العميل', 'type' => 'select', 'name' => 'customer_id', 'data' => ['options_source' => 'customers', 'has_empty' => true]],
            ['title' => 'رقم جوال العميل', 'type' => 'input', 'name' => 'mobile_no'],
            ['title' => 'نوع العقد', 'type' => 'select', 'name' => 'category_of_contract', 'data' => ['options_source' => 'categories_of_contracts', 'has_empty' => true]],
            ['title' => ' نوع المولد', 'type' => 'input', 'name' => 'product_type'],
            ['title' => ' موديل المولد', 'type' => 'input', 'name' => 'product_model'],
            ['title' => ' سعة المولد', 'type' => 'input', 'name' => 'product_capacity'],
            ['title' =>  ' تاريخ بدء العقد', 'type' => 'input', 'name' => 'contract_starting_date', 'date_range' => true],
            ['title' =>  ' تاريخ نهاية العقد', 'type' => 'input', 'name' => 'contract_ending_date', 'date_range' => true],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],

        ];

        $columns = [
            ['title' => 'رقم العقد', 'column' => 'product_for_user.contract_number'],
            ['title' => 'الاسم العميل', 'column' => 'customer.full_name'],
            ['title' => 'اسم الموظف المكلف', 'column' => 'product_for_user.employee.full_name'],
            ['title' => 'نوع العقد', 'column' => 'category_of_contract.name'],
            ['title' => ' تفاصيل أخرى للعقد', 'column' => 'note.content',  'formatter' => 'contentForContract'],
            ['title' => 'الرقم التسلسلي', 'column' => 'product_for_user.serial_number'],
            ['title' => ' نوع المولد', 'column' => 'product_for_user.product_type'],
            ['title' => ' موديل المولد', 'column' => 'product_for_user.product_model'],
            ['title' => ' سعة المولد', 'column' => 'product_for_user.product_capacity'],
            ['title' => ' سعر البيع', 'column' => 'product_for_user.product_price'],
            ['title' => ' نوع العملة', 'column' => 'product_for_user.currency.name'],
            ['title' => ' تاريخ بدء العقد', 'column' => 'product_for_user.contract_starting_date'],
            ['title' => ' تاريخ نهاية العقد', 'column' => 'product_for_user.contract_ending_date'],
            ['title' => 'صورة العقد ', 'column' => 'contract_image_url',  'formatter' => 'contract_image'],
            ['title' => 'بيانات إضافية للمولد', 'column' => 'product_for_user.other_details',  'formatter' => 'contentForProduct'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
}
