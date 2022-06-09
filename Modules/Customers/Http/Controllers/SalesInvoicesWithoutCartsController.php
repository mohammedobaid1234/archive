<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SalesInvoicesWithoutCartsController extends Controller{
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
        \Auth::user()->authorize('customers_module_sales_invoices_without_cart_manage');

        $data['activePage'] = ['sales_invoices_without_cart' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('customers::sales_invoices_without_cart', $data);
    }
    
    public function datatable(Request $request){
        \Auth::user()->authorize('customers_module_sales_invoices_without_cart_manage');

        $eloquent = $this->model::with([]);

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
                $eloquent->where('transaction_number', "LIKE", "%".trim($request->transaction_number)."%");
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
            ['title' =>  'تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'رقم الإيصال', 'column' => 'transaction_number'],
            ['title' => 'الاسم العميل', 'column' => 'customer.full_name','formatter' => 'customerProfile'],
            ['title' => 'عنوان العميل', 'column' => 'address'],
            ['title' => 'اسم الموظف المكلف', 'column' => 'employee.full_name'],
            ['title' => 'الإجمالي', 'column' => 'total'],
            ['title' => 'المدفوع', 'column' => 'paid'],
            ['title' => 'المتبقي', 'column' => 'remaining'],
            ['title' => 'صورة الفاتورة ', 'column' => 'sale_invoice_image_url',  'formatter' => 'sales_invoice'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }


}
