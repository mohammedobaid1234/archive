<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ContractsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات العقود";
    private $model = \Modules\Customers\Entities\Contract::class;

    public function index(Request $request){
        $response = $this->model::with([]);

        if ($request->has('search') && trim($request->search)) {
            if (is_numeric(trim($request->search))) {
                $response->where('mobile_no', trim($request->search));
            } else {
                $response->where((trim($request->where_like_column) === "" ? 'full_name' : trim($request->where_like_column)), 'like', ('%' . trim($request->search) . '%'));
            }
        }

        if ((int) trim($request->all)) {
            return ["data" => $response->get()];
        }

        return $response->paginate(20);
    }

    public function manage(){
        \Auth::user()->authorize('customers_module_contracts_manage');

        $data['activePage'] = ['archive' => 'contracts'];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('customers::contracts', $data);
    }

    public function datatable(Request $request){
        \Auth::user()->authorize('customers_module_contracts_manage');

        $eloquent = $this->model::with(['customer', 'created_by_user', 'category_of_contract', 'note','product_for_user']);

        if ((int) $request->filters_status) {
            if (trim($request->contract_number) !== "") {
                $eloquent->whereHas('product_for_user', function($query) use ($request){
                    $query->where('contract_number', "LIKE" , "%". trim($request->contract_number) . "%");
                });
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
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
            if (trim($request->contract_starting_date) !== '') {
                $eloquent->WhereStaredAt($request->contract_starting_date);
            }
            if (trim($request->contract_ending_date) !== '') {
                $eloquent->WhereEndedAt($request->contract_ending_date);
            }
        }

        $filters = [
            ['title' => 'رقم الملف', 'type' => 'input', 'name' => 'contract_number'],
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
            ['title' => 'اسم العميل', 'column' => 'customer.full_name'],
            ['title' => 'اسم الموظف المكلف', 'column' => 'product_for_user.employee.full_name'],
            ['title' => ' تفاصيل أخرى للعقد', 'column' => 'note.content',  'formatter' => 'contentForContract'],
            ['title' => 'الرقم التسلسلي', 'column' => 'product_for_user.serial_number'],
            ['title' => ' نوع المولد', 'column' => 'product_for_user.product_type'],
            ['title' => ' الموديل ', 'column' => 'product_for_user.product_model'],
            ['title' => ' القدرة ', 'column' => 'product_for_user.product_capacity'],
            ['title' => 'نوع العقد', 'column' => 'category_of_contract.name'],
            ['title' => ' قيمة العقد ', 'column' => 'product_for_user.product_price'],
            ['title' => ' نوع العملة', 'column' => 'product_for_user.currency.name'],
            ['title' => ' تاريخ بدء العقد', 'column' => 'product_for_user.contract_starting_date'],
            ['title' => ' تاريخ نهاية العقد', 'column' => 'product_for_user.contract_ending_date'],
            ['title' => 'صورة العقد ', 'column' => 'contract_image_url',  'formatter' => 'contract_image'],
            ['title' => 'بيانات إضافية للمولد', 'column' => 'product_for_user.other_details',  'formatter' => 'contentForProduct'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        
        \Auth::user()->authorize('customers_module_contracts_store');

        $request->validate([
            'contract_number' => 'required',
            'customer_id' => 'required',
            'employee_id' => 'required',
            'product_type' => 'required',
            'product_model' => 'required',
            'product_capacity' => 'required',
            'product_price' => 'required',
            'currency_id' => 'required',
            'other_details' => 'required|string',
            'category_of_contract' => 'required',
            'serial_number' => 'required',
            'content' => 'required|string',
            'image' => 'required',
        ]);
         $product = \Modules\Customers\Entities\ProductForCustomer::where('contract_number', $request->contract_number)->first();

        if($product){
            return response()->json(['message' => "رقم العقد موجود مسبقا."], 403);
        }
        $customer = \Modules\Customers\Entities\Customer::where('id', $request->customer_id)->first();
        if(!$customer){
            return response()->json(['message' => "يرجى التحقق من العميل."], 403);
        }
        $employee_id = \Modules\Employees\Entities\Employee::whereId($request->employee_id)->first();
        if(!$employee_id){
            return response()->json(['message' => "يرجى التحقق من الموظف المكلف."], 403);
        }
        $categories_of_contacts = \Modules\Customers\Entities\CategoriesOfContracts::where('id', $request->category_of_contract)->first();
        if(!$categories_of_contacts ){
            return response()->json(['message' => "يرجى التحقق من نوع العقد."], 403);
        }
       if(!$request->contract_starting_date){
           $request->merge([
               'contract_starting_date' => now()
           ]);
       }
       if($categories_of_contacts->id == 2){
        if(!$request->contract_ending_date){
        return response()->json(['message' => "يرجى إضافة تاريخ انتهاء العقد ."], 403);
        } 
        if($request->contract_starting_date > $request->contract_ending_date){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
           }
       }
       if($categories_of_contacts->id == 1){
        if($request->contract_ending_date){
            return response()->json(['message' => "يرجى إزالة تاريخ انتهاء العقد ."], 403);
            }  
       }

        \DB::beginTransaction();
        try {
            $contract = new \Modules\Customers\Entities\Contract;
            $contract->category_of_contract = $request->category_of_contract;
            $contract->customer_id = $request->customer_id;
            $contract->created_by = \Auth::user()->id;
            $contract->save();

            $note =new \Modules\Customers\Entities\Note;
            $note->content = $request->content;
            $note->customer_id = $request->customer_id;
            $note->contract_id = $contract->id;
            $note->created_by = \Auth::user()->id;
            $note->save();
            
            $ProductForCustomer = new \Modules\Customers\Entities\ProductForCustomer;
            $ProductForCustomer->contract_number = $request->contract_number;
            $ProductForCustomer->contract_id= $contract->id;
            $ProductForCustomer->serial_number = $request->serial_number;
            $ProductForCustomer->employee_id = $request->employee_id;
            $ProductForCustomer->customer_id = $request->customer_id;
            $ProductForCustomer->customer_id = $request->customer_id;
            $ProductForCustomer->product_type = $request->product_type;
            $ProductForCustomer->product_model = $request->product_model;
            $ProductForCustomer->product_capacity = $request->product_capacity;
            $ProductForCustomer->product_price = $request->product_price;
            $ProductForCustomer->other_details = $request->other_details;
            $ProductForCustomer->currency_id = $request->currency_id;
            $ProductForCustomer->contract_starting_date = $request->contract_starting_date;
            $ProductForCustomer->contract_ending_date = $request->contract_ending_date;
            $ProductForCustomer->save();
            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "contract_image";

                $contract->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }
    public function create(){
        return [
            "title" => "اضافة عقد جديد",
            "inputs" => [
                 ['title' => 'رقم العقد ', 'input' => 'input', 'name' => 'contract_number', 'required' => true,'operations' => ['show' => ['text' => 'product_for_user.contract_number']]],
                [
                    ['title' => 'اسم العميل', 'input' => 'select', 'name' => 'customer_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'customers', 'placeholder' => 'اسم العميل...'],'operations' => ['show' => ['text' => 'customer.full_name', 'id' => 'customer_id']]],
                    ['title' => 'اسم الموظف المكلف', 'input' => 'select', 'name' => 'employee_id', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'employees', 'placeholder' => 'اسم  الموظف المكلف...'],'operations' => ['show' => ['text' => 'product_for_user.employee.full_name', 'id' => 'product_for_user.employee.id']]],
                
                ],
                [
                    ['title' => 'النوع ', 'input' => 'input', 'name' => 'product_type', 'required' => true,'operations' => ['show' => ['text' => 'product_for_user.product_type']]],
                    ['title' => 'الموديل ', 'input' => 'input', 'name' => 'product_model', 'required' => true,'operations' => ['show' => ['text' => 'product_for_user.product_model']]],
                    ['title' => 'القدرة ', 'input' => 'input', 'name' => 'product_capacity', 'required' => true,'operations' => ['show' => ['text' => 'product_for_user.product_capacity']]],
                ],
                [
                    ['title' => 'سعر البيع', 'input' => 'input', 'name' => 'product_price', 'required' => true, 'classes' => ['numeric'],'operations' => ['show' => ['text' => 'product_for_user.product_price']]],
                    ['title' => 'نوع العملة', 'input' => 'select', 'name' => 'currency_id', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'currencies', 'placeholder' => 'نوع العملة...'],'operations' => ['show' => ['text' => 'product_for_user.currency.name', 'id' => 'product_for_user.currency.id'],'update' => ['text' => 'product_for_user.currency.name', 'id' => 'product_for_user.currency.id']]],
                ],
                ['title' => 'تفاصيل أخرى للمولد', 'input' => 'textarea', 'name' => 'other_details', 'required' => true, 'placeholder' => '  تفاصيل أخرى للمولد ...','operations' => ['show' => ['text' => 'product_for_user.other_details']]],
                [
                    ['title' =>  'نوع العقد', 'input' => 'select', 'name' => 'category_of_contract', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'categories_of_contracts', 'placeholder' =>'نوع العقد...'],'operations' => ['show' => ['text' => 'category_of_contract.name', 'id' => 'category_of_contract.id'],'update' => ['text' => 'category_of_contract.name', 'id' => 'category_of_contract.id']]],
                    ['title' => 'تاريخ بداية العقد', 'input' => 'input', 'name' => 'contract_starting_date', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'product_for_user.contract_starting_date']]],
                    ['title' =>  ' (للصيانة فقط)تاريخ نهاية العقد', 'input' => 'input', 'name' => 'contract_ending_date', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'product_for_user.contract_ending_date']]],
                    
                ],
                ['title' => ' الرقم التسلسلي', 'input' => 'input', 'name' => 'serial_number','operations' => ['show' => ['text' => 'product_for_user.serial_number']]],
                ['title' => 'تفاصيل أخرى العقد', 'input' => 'textarea', 'name' => 'content', 'required' => true, 'placeholder' =>'تفاصيل أخرى العقد ...','operations' => ['show' => ['text' => 'note.content']]],

                ['title' => 'صورة العقد', 'input' => 'input','type' => 'file', 'name' => 'image','operations' => ['show' => ['text' => 'image'],'update' => ['text' => 'image'],]]
            ]
        ];
    }

    public function show($id){
        return $this->model::with(['customer', 'created_by_user', 'category_of_contract', 'note','product_for_user'])->whereId($id)->first();
    }

    public function update(Request $request, $contract_id){
        \Auth::user()->authorize('customers_module_contracts_update');
        $request->validate([
            'contract_number' => 'required',
            'customer_id' => 'required',
            'employee_id' => 'required',
            'product_type' => 'required',
            'product_model' => 'required',
            'product_capacity' => 'required',
            'product_price' => 'required',
            'currency_id' => 'required',
            'other_details' => 'required|string',
            'category_of_contract' => 'required',
            'serial_number' => 'required',
            'content' => 'required|string',
        ]);
        $product = \Modules\Customers\Entities\ProductForCustomer::where('contract_id', '<>', $contract_id)->where('contract_number', $request->contract_number)->first();
        if($product){
            return response()->json(['message' => "رقم العقد موجود مسبقا."], 403);
        }
        $customer = \Modules\Customers\Entities\Customer::where('id', $request->customer_id)->first();
        if(!$customer){
            return response()->json(['message' => "يرجى التحقق من العميل."], 403);
        }
        $employee_id = \Modules\Employees\Entities\Employee::whereId($request->employee_id)->first();
        if(!$employee_id){
            return response()->json(['message' => "يرجى التحقق من الموظف المكلف."], 403);
        }
        $categories_of_contacts = \Modules\Customers\Entities\CategoriesOfContracts::where('id', $request->category_of_contract)->first();
        if(!$categories_of_contacts ){
            return response()->json(['message' => "يرجى التحقق من نوع العقد."], 403);
        }
       if(!$request->contract_starting_date){
           $request->merge([
               'contract_starting_date' => now()
           ]);
       }
       if($categories_of_contacts->id == 2){
        if(!$request->contract_ending_date){
        return response()->json(['message' => "يرجى إضافة تاريخ انتهاء العقد ."], 403);
        } 
        if($request->contract_starting_date > $request->contract_ending_date){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
           }
       }
       if($categories_of_contacts->id == 1){
        if($request->contract_ending_date){
            return response()->json(['message' => "يرجى إزالة تاريخ انتهاء العقد ."], 403);
            }  
       }

        \DB::beginTransaction();
        try {
            $contract = \Modules\Customers\Entities\Contract::whereId($contract_id)->first();
            $contract->category_of_contract = $request->category_of_contract;
            $contract->customer_id = $request->customer_id;
            $contract->created_by = \Auth::user()->id;
            $contract->save();

            $note = \Modules\Customers\Entities\Note::where('contract_id', $contract_id)->first();
            $note->content = $request->content;
            $note->customer_id = $request->customer_id;
            $note->created_by = \Auth::user()->id;
            $note->save();
            
            $ProductForCustomer = \Modules\Customers\Entities\ProductForCustomer::where('contract_id', $contract_id)->first();;
            $ProductForCustomer->contract_number = $request->contract_number;
            $ProductForCustomer->serial_number = $request->serial_number;
            $ProductForCustomer->employee_id = $request->employee_id;
            $ProductForCustomer->customer_id = $request->customer_id;
            $ProductForCustomer->customer_id = $request->customer_id;
            $ProductForCustomer->product_type = $request->product_type;
            $ProductForCustomer->product_model = $request->product_model;
            $ProductForCustomer->product_capacity = $request->product_capacity;
            $ProductForCustomer->product_price = $request->product_price;
            $ProductForCustomer->other_details = $request->other_details;
            $ProductForCustomer->currency_id = $request->currency_id;
            $ProductForCustomer->contract_starting_date = $request->contract_starting_date;
            $ProductForCustomer->contract_ending_date = $request->contract_ending_date;
            $ProductForCustomer->save();
            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "contract_image";

                $contract->addMediaFromRequest('image[0]')
                        ->usingFileName($media_new_name)
                        ->usingName($request->file('image')[0]->getClientOriginalName())
                        ->toMediaCollection($collection);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }

}
