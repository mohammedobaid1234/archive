<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChecksController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات الشيكات";
    private $model = \Modules\Customers\Entities\Check::class;


    public function manage(){
        \Auth::user()->authorize('customers_module_checks_manage');

        $data['activePage'] = ['checks' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('customers::checks', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('customers_module_checks_manage');

        $eloquent = $this->model::with(['customer', 'created_by_user', 'currency', 'bank','customer_payment_date','employee']);

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
            if (trim($request->check_number) !== "") {
                $eloquent->where('check_number', 'LIKE', "%".trim($request->check_number).'%');
            }
            if (trim($request->check_number) !== "") {
                $eloquent->where('check_number', 'LIKE', "%".trim($request->check_number).'%');
            }
            if (trim($request->check_number) !== "") {
                $eloquent->where('check_number', 'LIKE', "%".trim($request->check_number).'%');
            }
            if (trim($request->due_date) !== '') {
                $eloquent->WhereDueDate($request->due_date);
            }
           
            if (trim($request->additional_details) !== "") {
                $eloquent->where('additional_details', 'LIKE', "%".trim($request->additional_details).'%');
            }
            if (trim($request->created_by) !== "") {
                $eloquent->whereHas('created_by_user', function($query) use ($request){
                    $query->where('name', "LIKE" , "%". trim($request->created_by_user) . "%");
                });
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
            if (trim($request->type_of_checks) !== "") {
                $eloquent->where('type', $request->type_of_checks);
            }
          
        }

        $filters = [
            ['title' => ' رقم الشيك', 'type' => 'input', 'name' => 'check_number'],
            ['title' => ' اسم البنك لصرف الشيك', 'type' => 'select', 'name' => 'bank_id', 'data' => ['options_source' => 'banks', 'has_empty' => true]],
            ['title' => ' نوع الشيك', 'type' => 'select', 'name' => 'type_of_checks', 'data' => ['options_source' => 'type_of_checks', 'has_empty' => true]],
            ['title' =>  ' تاريخ إستحقاق الشيك', 'type' => 'input', 'name' => 'due_date', 'date_range' => true],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'رقم الشيك', 'column' => 'check_number'],
            ['title' => 'الموظف المسؤول', 'column' => 'employee.full_name' ],
            ['title' => 'اسم البنك لصرف الشيك', 'column' => 'bank.name'],
            ['title' => 'الاسم المستفيد / الساحب', 'column' => 'customer.full_name','formatter' => 'customerProfile' ],
            ['title' => 'قيمة الشيك ', 'column' => 'amount'],
            ['title' => 'نوع العملة', 'column' => 'currency.name'],
            ['title' => 'نوع الشيك', 'column' => 'type','formatter' => 'typeOfCheck'],
            ['title' => ' تاريخ صرف الشيك', 'column' => 'due_date'],
            ['title' => "تفاصيل أخرى", 'column' => 'additional_details','formatter' => 'additionalDetails'],
            ['title' => "صورة الشيك", 'column' => 'image','formatter' => 'image'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        
        \Auth::user()->authorize('customers_module_check_store');

        $request->validate([
            'check_number' => 'required',
            'customer_id' => 'required',
            'amount' => 'required',
            'currency_id' => 'required',
            'type' => 'required',
            'due_date' => 'required',
            'bank_id' => 'required',
            'image' => 'required',
        ]);
         $check_number = \Modules\Customers\Entities\Check::where('id', $request->check_number)->first();

        if($check_number){
            return response()->json(['message' => "رقم الشيك موجود مسبقا."], 403);
        }
        $customer = \Modules\Customers\Entities\Customer::where('id', $request->customer_id)->first();
        if(!$customer){
            return response()->json(['message' => "يرجى التحقق من العميل."], 403);
        }
       
        if(!$request->bank_id){
        return response()->json(['message' => "يرجى التحقق من اسم البنك ."], 403);
        } 
        if(!$request->due_date){
            return response()->json(['message' => "يرجى إضافة تاريخ إستحقاق الشيك ."], 403);
        }
        if($request->due_date < now()){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
        }

        \DB::beginTransaction();
        try {
            $check = new \Modules\Customers\Entities\Check();
            $check->check_number = $request->check_number;
            $check->customer_id = $request->customer_id;
            $check->amount = $request->amount;
            $check->employee_id = $request->employee_id;
            $check->currency_id = $request->currency_id;
            $check->due_date = $request->due_date;
            $check->bank_id = $request->bank_id;
            $check->due_date = $request->due_date;
            $check->additional_details = $request->additional_details;
            $check->created_by = \Auth::user()->id;
            $check->save();
            if($request->contract_id){
            $contract = \Modules\Customers\Entities\ProductForCustomer::where('contract_number',$request->contract_id)->first();
            if($contract){
                $customer_payment = new \Modules\Customers\Entities\CustomerPaymentsDate;
                $customer_payment->contract_number  = $request->contract_id ;
                $customer_payment->employee_id  = $request->employee_id ;
                $customer_payment->label  = 'شيك'. $request->check_number ;
                $customer_payment->payment_id  = $check->id ;
                $customer_payment->payment_type  = 'Modules\Customers\Entities\Check' ;
                $customer_payment->amount  = $check->amount ;
                $customer_payment->currency_id  = $check->currency_id ;
                $customer_payment->due_date = $check->due_date;
                $customer_payment->created_by = \Auth::user()->id;
                $customer_payment->save();
             }
            }else{
                return response()->json(['message' => "رقم العقد غير موجود."], 403);
            }

            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "check_image";

                $check->addMediaFromRequest('image[0]')
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
            "title" => "اضافة إيصال جديد",
            "inputs" => [
                ['title' => 'اسم الموظف المسؤول ', 'input' => 'select', 'name' => 'employee_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'employees', 'placeholder' => 'اسم الموظف المسؤول...'],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee_id']]],
                ['title' => 'رقم الشيك ', 'input' => 'input', 'name' => 'check_number', 'operations' => ['show' => ['text' => 'check_number']]],
                ['title' => 'اسم المستفيد /الساحب ', 'input' => 'select', 'name' => 'customer_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'customers', 'placeholder' => 'اسم العميل...'],'operations' => ['show' => ['text' => 'customer.full_name', 'id' => 'customer_id']]],
                ['title' => 'نوع الشيك', 'input' => 'select', 'name' => 'type', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'type_of_checks', 'placeholder' => 'نوع الشيك...'],'operations' => ['show' => ['text' => 'type', 'id' => 'type']]],
                ['title' => '(في حال كان دفعة لعقد) رقم العقد', 'input' => 'input', 'name' => 'contract_id', 'operations' => ['show' => ['text' => 'customer_payment_date.contract_number']]],
                
                ['title' => ' اسم البنك', 'input' => 'select', 'name' => 'bank_id',  'classes' => ['select2'], 'data' => ['options_source' => 'banks', 'placeholder' => 'اسم البنك لصرف الشيك...'],'operations' => ['show' => ['text' => 'bank.name', 'id' => 'bank.id'],'update' => ['text' => 'bank.name', 'id' => 'bank.id']]],
                [
                    ['title' => 'قيمة الشيك ', 'input' => 'input', 'name' => 'amount',  'classes' => ['numeric'],'required' => true,'operations' => ['show' => ['text' => 'amount'],]],
                    ['title' => 'نوع العملة', 'input' => 'select', 'name' => 'currency_id', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'currencies', 'placeholder' => 'نوع العملة...'],'operations' => ['show' => ['text' => 'currency.name', 'id' => 'currency.id'],'update' => ['text' => 'currency.name', 'id' => 'currency.id']]],
                ],
                
                ['title' => 'تاريخ الاستحقاق الشيك ', 'input' => 'input', 'name' => 'due_date', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'due_date']]],
                ['title' => 'تفاصيل إضافية ...', 'input' => 'textarea', 'name' => 'additional_details',  'placeholder' => 'تفاصيل إضافية ...','operations' => ['show' => ['text' => 'additional_details']]],

                ['title' => 'صورة الشيك', 'input' => 'input','type' => 'file', 'name' => 'image','operations' => ['show' => ['text' => 'image'],'update' => ['text' => 'image'],]]
            ]
        ];
    }
    public function show($id){
        return $this->model::with(['customer', 'created_by_user','currency', 'bank','customer_payment_date','employee'])->whereId($id)->first();
    }

    public function update(Request $request, $id){
        
        \Auth::user()->authorize('customers_module_check_update');

        $request->validate([
            'check_number' => 'required',
            'customer_id' => 'required',
            'employee_id' => 'required',
            'amount' => 'required',
            'currency_id' => 'required',
            'type' => 'required',
            'due_date' => 'required',
            'bank_id' => 'required',
        ]);
         $check_number = \Modules\Customers\Entities\Check::where('id', '<>', $id)->where('id', $request->check_number)->first();

        if($check_number){
            return response()->json(['message' => "رقم الشيك موجود مسبقا."], 403);
        }
        $customer = \Modules\Customers\Entities\Customer::where('id', $request->customer_id)->first();
        if(!$customer){
            return response()->json(['message' => "يرجى التحقق من العميل."], 403);
        }
       
        if(!$request->bank_id){
        return response()->json(['message' => "يرجى التحقق من اسم البنك ."], 403);
        } 
        if(!$request->due_date){
            return response()->json(['message' => "يرجى إضافة تاريخ إستحقاق الشيك ."], 403);
        }
     

        \DB::beginTransaction();
        try {
            $check = \Modules\Customers\Entities\Check::whereId($id)->first();
            $check->check_number = $request->check_number;
            $check->customer_id = $request->customer_id;
            $check->employee_id = $request->employee_id;
            $check->amount = $request->amount;
            $check->currency_id = $request->currency_id;
            $check->due_date = $request->due_date;
            $check->bank_id = $request->bank_id;
            $check->due_date = $request->due_date;
            $check->additional_details = $request->additional_details;
            $check->created_by = \Auth::user()->id;
            $check->save();
            if($request->contract_id && $request->type == 'وارد'){
                $contract = \Modules\Customers\Entities\ProductForCustomer::where('contract_number',$request->contract_id)->first();
                if($contract){
                    $customer_payment =  \Modules\Customers\Entities\CustomerPaymentsDate::where('payment_id', $check->id)
                                         ->where('payment_type', 'Modules\Customers\Entities\Check')->first();
                    if($customer_payment){
                        $customer_payment->contract_number  = $request->contract_id ;
                        $customer_payment->employee_id  = $request->employee_id ;
                        $customer_payment->label  = 'شيك'. $request->check_number ;
                        $customer_payment->payment_id  = $check->id ;
                        $customer_payment->payment_type  = 'Modules\Customers\Entities\Check' ;
                        $customer_payment->amount  = $check->amount ;
                        $customer_payment->currency_id  = $check->currency_id ;
                        $customer_payment->due_date = $check->due_date;
                        $customer_payment->created_by = \Auth::user()->id;
                        $customer_payment->save();
                    }else{
                        $customer_payment = new \Modules\Customers\Entities\CustomerPaymentsDate;
                        $customer_payment->contract_number  = $request->contract_id ;
                        $customer_payment->employee_id  = $request->employee_id ;
                        $customer_payment->label  = 'شيك'. $request->check_number ;
                        $customer_payment->payment_id  = $check->id ;
                        $customer_payment->payment_type  = 'Modules\Customers\Entities\Check' ;
                        $customer_payment->amount  = $check->amount ;
                        $customer_payment->currency_id  = $check->currency_id ;
                        $customer_payment->due_date = $check->due_date;
                        $customer_payment->created_by = \Auth::user()->id;
                        $customer_payment->save();
                    }
                    
                }else{
                      return response()->json(['message' => "رقم العقد غير موجود."], 403);
                }
            } 

            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "check_image";

                $check->addMediaFromRequest('image[0]')
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
