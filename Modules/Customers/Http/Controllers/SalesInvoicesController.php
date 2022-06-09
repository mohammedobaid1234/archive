<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SalesInvoicesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات المبيعات";
    private $model = \Modules\Products\Entities\Cart::class;

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

        $eloquent = $this->model::with(['sale_invoice','product']);

        if ((int) $request->filters_status) {
            if (trim($request->customer_id) !== '') {
                $eloquent->whereHas('sale_invoice.customer', function($query) use ($request){
                    $query->where('id', trim($request->customer_id));
                });
            }
            if (trim($request->mobile_no) !== '') {
                $eloquent->whereHas('sale_invoice.customer', function($query) use ($request){
                    $query->where('mobile_no', "LIKE" , "%". trim($request->mobile_no) . "%");
                });
            }
            if (trim($request->employee_id) !== '') {
                $eloquent->whereHas('sale_invoice.employee', function($query) use ($request){
                    $query->where('id', trim($request->employee_id));
                });
            }
            if (trim($request->transaction_number) !== "") {
                $eloquent->whereHas('sale_invoice', function($query) use ($request){
                    $query->where('transaction_number', "%".trim($request->transaction_number)."%");
                });
            }

            if (trim($request->product_id) !== "") {
                $eloquent->whereHas('product', function($query) use ($request){
                    $query->where('id',trim($request->product_id));
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
            ['title' => ' المنتج', 'type' => 'select', 'name' => 'product_id', 'data' => ['options_source' => 'products', 'has_empty' => true]],
            ['title' =>  'تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'رقم الإيصال', 'column' => 'sale_invoice.transaction_number'],
            ['title' => 'الاسم العميل', 'column' => 'sale_invoice.customer.full_name','formatter' => 'customer'],
            ['title' => 'عنوان العميل', 'column' => 'sale_invoice.address'],
            ['title' => ' رقم المنتج', 'column' => 'product.name'],
            ['title' => 'اسم الموظف المكلف', 'column' => 'sale_invoice.employee.full_name'],
            ['title' => 'الكمية ', 'column' => 'quantity'],
            ['title' => 'سعر الوحدة', 'column' => 'price_of_unit'],
            ['title' => 'الإجمالي', 'column' => 'total'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'sale_invoice.created_by_user.name'],
            // ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
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
                ['title' => 'المبلغ المدفوع', 'input' => 'input','type' => 'input', 'name' => 'paid','operations' => ['show' => ['text' => 'paid'],'update' => ['text' => 'paid'],]],
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
        
        $request->validate([
            'transaction_number'=> 'required',
            'customer_id'=> 'required',
            'address'=> 'required',
            'employee_id'=> 'required',
            'product_id'=> 'required',
            'quantity'=> 'required',
            'price_of_unit'=> 'required',
            'paid'=> 'required',
        ]);

        $transaction_number = \Modules\Customers\Entities\SaleInvoice::where('transaction_number', $request->transaction_number)->count();
        if($transaction_number){
            return response()->json(['message' => "رقم الفاتورة موجود مسبقا."], 403);
        }
        $customer = \Modules\Customers\Entities\Customer::where('id', $request->customer_id)->first();
        if(!$customer){
            return response()->json(['message' => "يرجى التحقق من العميل."], 403);
        }
        $employee_id = \Modules\Employees\Entities\Employee::whereId($request->employee_id)->first();
        if(!$employee_id){
            return response()->json(['message' => "يرجى التحقق من الموظف المكلف."], 403);
        }

        \DB::beginTransaction();
        try {
            $product_id = $request->product_id[0];
            $price_of_unit = $request->price_of_unit[0];
            $quantity = $request->quantity[0];

            $products = explode(',',$product_id);
            $prices = explode(',',$price_of_unit);
            $quantities = explode(',',$quantity);
            if(count($products) == count($prices) && count($prices ) == count($quantities) ){
                $saleInvoice = new \Modules\Customers\Entities\SaleInvoice;
                $saleInvoice->transaction_number = $request->transaction_number; 
                $saleInvoice->employee_id = $request->employee_id; 
                $saleInvoice->customer_id = $request->customer_id; 
                $saleInvoice->address = $request->address; 
                $saleInvoice->paid = $request->paid; 
                $saleInvoice->created_by = \Auth::user()->id;
                $saleInvoice->save();
                $totals = 0;

                for ($i=0; $i <count($products) ; $i++) { 
                    $cart =  new \Modules\Products\Entities\Cart;
                    $cart->sale_invoice = $saleInvoice->id; 
                    $cart->product_id  = $products[$i]; 
                    $cart->price_of_unit  = $prices[$i]; 
                    $cart->quantity  = $quantities[$i]; 
                    $total = $prices[$i] * $quantities[$i];
                    $cart->total  = $total; 
                    
                    $totals += $total;
                    $cart->save();
                }
                $saleInvoice->total = $totals;
                $saleInvoice->remaining = $saleInvoice->total - $saleInvoice->paid = $request->paid;
                $saleInvoice->save();

                if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                    $extension = strtolower($request->file('image')[0]->extension());
                    $media_new_name = strtolower(md5(time())) . "." . $extension;
                    $collection = "sale_invoice_image";
    
                    $saleInvoice->addMediaFromRequest('image[0]')
                            ->usingFileName($media_new_name)
                            ->usingName($request->file('image')[0]->getClientOriginalName())
                            ->toMediaCollection($collection);
                }
            }else{
                return response()->json(['message' => 'Thats Error'], 403);

            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
            
    }
   

    
}
