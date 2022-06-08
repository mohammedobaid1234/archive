<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SalesInvoicesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات فواتير البيع";
    private $model = \Modules\Customers\Entities\SaleInvoice::class;

    public function index(Request $request){
        $response = $this->model::with([]);

        if ((int) trim($request->all)) {
            return ["data" => $response->get()];
        }

        return $response->paginate(20);
    }
    public function manage(){
        \Auth::user()->authorize('customers_module_sales_invoices_manage');

        $data['activePage'] = ['sales_invoices' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('customers::sales_invoices', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('customers_module_sales_invoices_manage');

        $eloquent = $this->model::with(['customer', 'created_by_user','employee', 'currency', 'bank']);

        if ((int) $request->filters_status) {
            if (trim($request->customer_id) !== '') {
                $eloquent->whereHas('customer', function($query) use ($request){
                    $query->where('id', trim($request->customer_id));
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

            if (trim($request->products_id) !== "") {
                $eloquent->whereHas('carts.product', function($query) use ($request){
                    $query->where('name',trim($request->products_id));
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
            ['title' => ' المنتج', 'type' => 'select', 'name' => 'products_id', 'data' => ['options_source' => 'products', 'has_empty' => true]],
            ['title' =>  'تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'رقم الإيصال', 'column' => 'transaction_number'],
            ['title' => 'الاسم العميل', 'column' => 'customer.full_name'],
            ['title' => 'عنوان العميل', 'column' => 'address'],
            ['title' => 'اسم الموظف المكلف', 'column' => 'employee.full_name'],
            ['title' => 'إجمالي السعر', 'column' => 'total'],
            ['title' => 'المبلع المدفوع', 'column' => 'paid'],
            ['title' => 'المبلع المتبقي', 'column' => 'remaining'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function create(){
        return [
            "title" => "اضافة عقد جديد",
            "inputs" => [
                 ['title' => 'رقم الفاتورة ', 'input' => 'input', 'name' => 'transaction_number', 'required' => true,'operations' => ['show' => ['text' => 'transaction_number']]],
                [
                    ['title' => 'اسم العميل', 'input' => 'select', 'name' => 'customer_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'customers', 'placeholder' => 'اسم العميل...'],'operations' => ['show' => ['text' => 'customer.full_name', 'id' => 'customer_id']]],
                    ['title' => 'عنوان العميل ', 'input' => 'input', 'name' => 'address', 'required' => true,'operations' => ['show' => ['text' => 'address']]],
                    ['title' => 'اسم الموظف المكلف', 'input' => 'select', 'name' => 'employee_id', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'employees', 'placeholder' => 'اسم  الموظف المكلف...'],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee.id']]],
                
                ],
                ['title' => 'صورة الفاتور', 'input' => 'input','type' => 'file', 'name' => 'image','operations' => ['show' => ['text' => 'image'],'update' => ['text' => 'image'],]],
                [
                    ['title' => 'اسم الصنف ', 'input' => 'select', 'name' => 'product_id[]', 'required' => true, 'classes' => ['child select2'], 'data' => ['options_source' => 'products'], 'operations' => ['show' => ['text' => 'product_id']]],
                    ['title' => 'الكمية ', 'input' => 'input', 'classes' => ['child'],'name' => 'quantity[]', 'required' => true,'operations' => ['show' => ['text' => 'quantity']]],
                    ['title' => 'سعر الوحدة ', 'input' => 'input','classes' => ['child'], 'name' => 'price_of_unit[]', 'required' => true,'operations' => ['show' => ['text' => 'price_of_unit']]],
                ],
               
            ]
        ];
    }
    public function store(Request $request){
        return response()->json($request);
    }

    
}
