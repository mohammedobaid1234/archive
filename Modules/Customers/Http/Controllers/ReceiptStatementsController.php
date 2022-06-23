<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReceiptStatementsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات إيصالات القبض";
    private $model = \Modules\Customers\Entities\ReceiptStatement::class;

    public function index(Request $request){
        $response = $this->model::with([]);

        if ((int) trim($request->all)) {
            return ["data" => $response->get()];
        }

        return $response->paginate(20);
    }

    public function manage(){
        \Auth::user()->authorize('customers_module_receipt_statements_manage');

        $data['activePage'] = ['receipt_statements' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('customers::receipt_statements', $data);
    }

    public function datatable(Request $request){
        \Auth::user()->authorize('customers_module_receipt_statements_manage');

        $eloquent = $this->model::with(['customer', 'created_by_user','employee', 'currency', 'bank']);

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
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        
        \Auth::user()->authorize('customers_module_receipt_statements_store');

        $request->validate([
            'transaction_number' => 'required',
            'customer_id' => 'required',
            'basic_amount' => 'required',
            'received_amount' => 'required',
            'remaining_amount' => 'required',
            'currency_id' => 'required',
            'transaction_date' => 'required',
            'transaction_type' => 'required',
            'payment_method' => 'required',
            'opposite' => 'required',
            'image' => 'required',
        ]);
         $transaction_number = \Modules\Customers\Entities\ReceiptStatement::where('transaction_number', $request->transaction_number)->first();

        if($transaction_number){
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
       
       if($request->check_number){
        if(!$request->bank_id){
        return response()->json(['message' => "يرجى التحقق من اسم البنك ."], 403);
        } 
       
        if(!$request->check_due_date){
            return response()->json(['message' => "يرجى إضافة تاريخ إستحقاق الشيك ."], 403);
        }
        if(!$request->check_image){
            return response()->json(['message' => "يرجى إضافة صورة الشيك ."], 403);
        }
       }

       if($request->bank_id){
        if(!$request->check_number){
        return response()->json(['message' => "يرجى إضتافة رقم الشيك ."], 403);
        } 
        if(!$request->check_image){
            return response()->json(['message' => "يرجى إضافة صورة الشيك ."], 403);
        }
        if(!$request->check_due_date){
            return response()->json(['message' => "يرجى إضافة تاريخ إستحقاق الشيك ."], 403);
        }
       }
       

        \DB::beginTransaction();
        try {
            $receiptStatement = new \Modules\Customers\Entities\ReceiptStatement();
            $receiptStatement->transaction_number = $request->transaction_number;
            $receiptStatement->customer_id = $request->customer_id;
            $receiptStatement->employee_id = $request->employee_id;
            $receiptStatement->basic_amount = $request->basic_amount;
            $receiptStatement->received_amount = $request->received_amount;
            $receiptStatement->remaining_amount = $request->remaining_amount;
            $receiptStatement->currency_id = $request->currency_id;
            $receiptStatement->transaction_date = $request->transaction_date;
            $receiptStatement->transaction_date = $request->transaction_date;
            $receiptStatement->transaction_type = $request->transaction_type;
            $receiptStatement->payment_method = $request->payment_method;
            $receiptStatement->check_number = $request->check_number;
            $receiptStatement->bank_id = $request->bank_id;
            $receiptStatement->currency_id  = $request->currency_id ;
            $receiptStatement->check_due_date = $request->check_due_date;
            $receiptStatement->next_due_date = $request->next_due_date;
            $receiptStatement->opposite = $request->opposite;
            $receiptStatement->other_terms = $request->other_terms;
            $receiptStatement->created_by = \Auth::user()->id;
            $receiptStatement->save();

            if($request->check_number){
                $check = \Modules\Customers\Entities\Check::where('check_number', $request->check_number)->first();
                if(!$check){
                    
                    $newCheck = new \Modules\Customers\Entities\Check;
                    $newCheck->check_number = $receiptStatement->check_number;
                    $newCheck->customer_id  = $receiptStatement->customer_id ;
                    $newCheck->amount  = $receiptStatement->received_amount ;
                    $newCheck->type   = 'وارد'  ;
                    $newCheck->due_date   = $receiptStatement->check_due_date ;
                    $newCheck->bank_id    = $receiptStatement->bank_id  ;
                    $newCheck->currency_id     = $receiptStatement->currency_id   ;
                    $newCheck->additional_details    = $receiptStatement->opposite  ;
                    $newCheck->employee_id     = $receiptStatement->employee_id   ;
                    $newCheck->created_by = \Auth::user()->id;
                    $newCheck->save();
                    if($request->hasFile('check_image') && $request->file('check_image')[0]->isValid()){
                        $extension = strtolower($request->file('check_image')[0]->extension());
                        $media_new_name = strtolower(md5(time())) . "." . $extension;
                        $collection = "check_image";
        
                        $newCheck->addMediaFromRequest('check_image[0]')
                                ->usingFileName($media_new_name)
                                ->usingName($request->file('check_image')[0]->getClientOriginalName())
                                ->toMediaCollection($collection);
                    }

                    $customer_payment = new \Modules\Customers\Entities\CustomerPaymentsDate;
                    $customer_payment->contract_number  =  $receiptStatement->transaction_number ;
                    $customer_payment->employee_id  = $newCheck->employee_id ;
                    $customer_payment->label  = 'إيصال قبض بواسطة شيك'. $newCheck->check_number ;
                    $customer_payment->payment_id  = $newCheck->id ;
                    $customer_payment->state  = 'تم_السداد' ;
                    $customer_payment->payment_type  = 'Modules\Customers\Entities\Check' ;
                    $customer_payment->amount  = $newCheck->amount ;
                    $customer_payment->currency_id  = $newCheck->currency_id ;
                    $customer_payment->due_date = $newCheck->due_date;
                    $customer_payment->created_by = \Auth::user()->id;
                    $customer_payment->save();

                }else{
                    $check->check_number = $receiptStatement->check_number;
                    $check->customer_id  = $receiptStatement->customer_id ;
                    $check->amount  = $receiptStatement->received_amount ;
                    $check->type   = 'وارد'  ;
                    $check->due_date   = $receiptStatement->check_due_date ;
                    $check->bank_id    = $receiptStatement->bank_id  ;
                    $check->currency_id     = $receiptStatement->currency_id   ;
                    $check->additional_details    = $receiptStatement->opposite  ;
                    $check->employee_id     = $receiptStatement->employee_id   ;
                    $check->created_by = \Auth::user()->id;
                    $check->save();
                    if($request->hasFile('check_image') && $request->file('check_image')[0]->isValid()){
                        $extension = strtolower($request->file('check_image')[0]->extension());
                        $media_new_name = strtolower(md5(time())) . "." . $extension;
                        $collection = "check_image";
        
                        $check->addMediaFromRequest('check_image[0]')
                                ->usingFileName($media_new_name)
                                ->usingName($request->file('check_image')[0]->getClientOriginalName())
                                ->toMediaCollection($collection);
                    }

                    $customer_payment =  \Modules\Customers\Entities\CustomerPaymentsDate::where('payment_id', $check->id)
                    ->where('payment_type', 'Modules\Customers\Entities\Check')->first();
                    $customer_payment->contract_number  = $receiptStatement->check_number ;
                    $customer_payment->employee_id  = $check->employee_id ;
                    $customer_payment->label  = 'إيصال قبض بواسطة شيك'. $check->check_number ;
                    $customer_payment->payment_id  = $check->id ;
                    $customer_payment->state  = 'تم_السداد' ;
                    $customer_payment->payment_type  = 'Modules\Customers\Entities\Check' ;
                    $customer_payment->amount  = $check->amount ;
                    $customer_payment->currency_id  = $check->currency_id ;
                    $customer_payment->due_date = $check->due_date;
                    $customer_payment->created_by = \Auth::user()->id;
                    $customer_payment->save();
                    
                }
            }else{
                $customer_payment = new \Modules\Customers\Entities\CustomerPaymentsDate;
                    $customer_payment->contract_number  =  $receiptStatement->transaction_number ;
                    $customer_payment->employee_id  = $receiptStatement->employee_id ;
                    $customer_payment->label  = 'إيصاال قبض'. $receiptStatement->transaction_number ;
                    $customer_payment->payment_id  = $receiptStatement->id ;
                    $customer_payment->state  = 'تم_السداد' ;
                    $customer_payment->payment_type  = 'Modules\Customers\Entities\ReceiptStatement' ;
                    $customer_payment->amount  = $receiptStatement->received_amount ;
                    $customer_payment->currency_id  = $receiptStatement->currency_id ;
                    $customer_payment->due_date = $receiptStatement->transaction_date;
                    $customer_payment->created_by = \Auth::user()->id;
                    $customer_payment->save();
            }
            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "receipt-statement_image";

                $receiptStatement->addMediaFromRequest('image[0]')
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
                 ['title' => 'رقم الإيصال ', 'input' => 'input', 'name' => 'transaction_number', 'required' => true,'operations' => ['show' => ['text' => 'transaction_number']]],
                [
                    ['title' => 'اسم العميل', 'input' => 'select', 'name' => 'customer_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'customers', 'placeholder' => 'اسم العميل...'],'operations' => ['show' => ['text' => 'customer.full_name', 'id' => 'customer_id']]],
                    ['title' => 'اسم الموظف المكلف', 'input' => 'select', 'name' => 'employee_id', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'employees', 'placeholder' => 'اسم  الموظف المكلف...'],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee.id']]],
                
                ],
                [
                    ['title' => 'المبلغ الاساسي ', 'input' => 'input', 'name' => 'basic_amount',  'classes' => ['numeric'],'required' => true,'operations' => ['show' => ['text' => 'basic_amount']]],
                    ['title' => 'المبلغ المستحق ', 'input' => 'input', 'name' => 'received_amount',  'classes' => ['numeric'],'required' => true,'operations' => ['show' => ['text' => 'received_amount']]],
                ],
                [
                    ['title' => 'المبلغ المتبقي ', 'input' => 'input', 'name' => 'remaining_amount',  'classes' => ['numeric'],'required' => true,'operations' => ['show' => ['text' => 'remaining_amount']]],
                    ['title' => 'نوع العملة', 'input' => 'select', 'name' => 'currency_id', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'currencies', 'placeholder' => 'نوع العملة...'],'operations' => ['show' => ['text' => 'currency.name', 'id' => 'currency.id'],'update' => ['text' => 'currency.name', 'id' => 'currency.id']]],
                ],
                
                [
                    ['title' => 'تاريخ المعاملة ', 'input' => 'input', 'name' => 'transaction_date', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'transaction_date']]],
                    ['title' => 'نوع المعاملة ', 'input' => 'input', 'name' => 'transaction_type',  'required' => true,'operations' => ['show' => ['text' => 'transaction_type']]],
                    
                ],
                ['title' => 'طريقة الدفع ',"rowIndex" => 1, 'input' => 'select', 'name' => 'payment_method', 'required' => true, 'classes' => ['select2'], 'data' => ['options_source' => 'payment_methods', 'placeholder' => 'طريقة الدفع ...'],'operations' => ['show' => ['text' => 'payment_method', 'id' => 'payment_method'],'update' => ['text' => 'payment_method', 'id' => 'payment_method']]],
                [
                    ['title' => 'رقم الشيك ', 'input' => 'input', 'name' => 'check_number', 'operations' => ['show' => ['text' => 'check_number']]],
                    ['title' => ' اسم البنك', 'input' => 'select', 'name' => 'bank_id',  'classes' => ['select2'], 'data' => ['options_source' => 'banks', 'placeholder' => 'اسم البنك لصرف الشيك...'],'operations' => ['show' => ['text' => 'bank.name', 'id' => 'bank.id'],'update' => ['text' => 'bank.name', 'id' => 'bank.id']]],
                    ['title' => 'تاريخ صرف الشيك ', 'input' => 'input', 'name' => 'check_due_date', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'check_due_date']]],
                ],
                ['title' => 'صورة الشيك', 'input' => 'input','type' => 'file', 'name' => 'check_image','operations' => ['show' => ['text' => 'check_image'],'update' => ['text' => 'check_image'],]],
                ['title' => 'تاريخ الاستحقاق للرصيد ', 'input' => 'input', 'name' => 'next_due_date', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'next_due_date']]],
                ['title' => 'وذلك مقابل ...', 'input' => 'textarea', 'name' => 'opposite', 'required' => true, 'placeholder' =>'وذلك مقابل ...','operations' => ['show' => ['text' => 'opposite']]],
                ['title' => 'شروط متغيرة ...', 'input' => 'textarea', 'name' => 'other_terms',  'placeholder' =>'شروط متغيرة ...','operations' => ['show' => ['text' => 'other_terms']]],

                ['title' => 'صورة الإيصال', 'input' => 'input','type' => 'file', 'name' => 'image','operations' => ['show' => ['text' => 'image'],'update' => ['text' => 'image'],]]
            ]
        ];
    }

    public function show($id){
        return $this->model::with(['customer', 'created_by_user','employee', 'currency', 'bank'])->whereId($id)->first();
    }

    public function update(Request $request, $receipt_statement){
        \Auth::user()->authorize('customers_module_receipt_statements_update');

        $request->validate([
            'transaction_number' => 'required',
            'customer_id' => 'required',
            'basic_amount' => 'required',
            'received_amount' => 'required',
            'remaining_amount' => 'required',
            'currency_id' => 'required',
            'transaction_date' => 'required',
            'transaction_type' => 'required',
            'payment_method' => 'required',
            'opposite' => 'required',
        ]);
         $transaction_number = \Modules\Customers\Entities\ReceiptStatement::where('id', '<>', $receipt_statement)->where('transaction_number', $request->transaction_number)->first();

        if($transaction_number){
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
       
       if($request->check_number){
        if(!$request->bank_id){
        return response()->json(['message' => "يرجى التحقق من اسم البنك ."], 403);
        } 

        if(!$request->check_due_date){
            return response()->json(['message' => "يرجى إضافة تاريخ إستحقاق الشيك ."], 403);
        }
       }

       if($request->bank_id){
        if(!$request->check_number){
        return response()->json(['message' => "يرجى إضتافة رقم الشيك ."], 403);
        } 
    
        if(!$request->check_due_date){
            return response()->json(['message' => "يرجى إضافة تاريخ إستحقاق الشيك ."], 403);
        }
       }
       
        \DB::beginTransaction();
        try {
            $receiptStatement = \Modules\Customers\Entities\ReceiptStatement::whereId($receipt_statement)->first();
            $receiptStatement->transaction_number = $request->transaction_number;
            $receiptStatement->customer_id = $request->customer_id;
            $receiptStatement->employee_id = $request->employee_id;
            $receiptStatement->basic_amount = $request->basic_amount;
            $receiptStatement->received_amount = $request->received_amount;
            $receiptStatement->remaining_amount = $request->remaining_amount;
            $receiptStatement->currency_id = $request->currency_id;
            $receiptStatement->transaction_date = $request->transaction_date;
            $receiptStatement->transaction_date = $request->transaction_date;
            $receiptStatement->transaction_type = $request->transaction_type;
            $receiptStatement->payment_method = $request->payment_method;
            $receiptStatement->check_number = $request->check_number;
            $receiptStatement->bank_id = $request->bank_id;
            $receiptStatement->check_due_date = $request->check_due_date;
            $receiptStatement->next_due_date = $request->next_due_date;
            $receiptStatement->opposite = $request->opposite;
            $receiptStatement->other_terms = $request->other_terms;
            $receiptStatement->created_by = \Auth::user()->id;
            $receiptStatement->save();
            if($request->check_number){
                $check = \Modules\Customers\Entities\Check::where('check_number', $request->check_number)->first();
                if(!$check){
                    $newCheck = new \Modules\Customers\Entities\Check;
                    $newCheck->check_number = $receiptStatement->check_number;
                    $newCheck->customer_id  = $receiptStatement->customer_id ;
                    $newCheck->amount  = $receiptStatement->received_amount ;
                    $newCheck->type   = 'وارد'  ;
                    $newCheck->due_date   = $receiptStatement->check_due_date ;
                    $newCheck->bank_id    = $receiptStatement->bank_id  ;
                    $newCheck->additional_details    = $receiptStatement->opposite  ;
                    $newCheck->employee_id     = $receiptStatement->employee_id   ;
                    $newCheck->created_by = \Auth::user()->id;
                    $newCheck->save();
                    if($request->hasFile('check_image') && $request->file('check_image')[0]->isValid()){
                        $extension = strtolower($request->file('check_image')[0]->extension());
                        $media_new_name = strtolower(md5(time())) . "." . $extension;
                        $collection = "check_image";
        
                        $newCheck->addMediaFromRequest('check_image[0]')
                                ->usingFileName($media_new_name)
                                ->usingName($request->file('check_image')[0]->getClientOriginalName())
                                ->toMediaCollection($collection);
                    }

                    $customer_payment = new \Modules\Customers\Entities\CustomerPaymentsDate;
                    $customer_payment->contract_number  =  $receiptStatement->transaction_number ;
                    $customer_payment->employee_id  = $newCheck->employee_id ;
                    $customer_payment->label  = 'إيصال قبض بواسطة شيك'. $newCheck->check_number ;
                    $customer_payment->payment_id  = $newCheck->id ;
                    $customer_payment->state  = 'تم_السداد' ;
                    $customer_payment->payment_type  = 'Modules\Customers\Entities\Check' ;
                    $customer_payment->amount  = $newCheck->amount ;
                    $customer_payment->currency_id  = $newCheck->currency_id ;
                    $customer_payment->due_date = $newCheck->due_date;
                    $customer_payment->created_by = \Auth::user()->id;
                    $customer_payment->save();

                }else{
                    $check->check_number = $receiptStatement->check_number;
                    $check->currency_id   = $receiptStatement->currency_id  ;
                    $check->customer_id  = $receiptStatement->customer_id ;
                    $check->amount  = $receiptStatement->received_amount ;
                    $check->type   = 'وارد'  ;
                    $check->due_date   = $receiptStatement->check_due_date ;
                    $check->bank_id    = $receiptStatement->bank_id  ;
                    $check->additional_details    = $receiptStatement->opposite  ;
                    $check->employee_id     = $receiptStatement->employee_id   ;
                    $check->created_by = \Auth::user()->id;
                    $check->save();
                    if($request->hasFile('check_image') && $request->file('check_image')[0]->isValid()){
                        $extension = strtolower($request->file('check_image')[0]->extension());
                        $media_new_name = strtolower(md5(time())) . "." . $extension;
                        $collection = "check_image";
        
                        $check->addMediaFromRequest('check_image[0]')
                                ->usingFileName($media_new_name)
                                ->usingName($request->file('check_image')[0]->getClientOriginalName())
                                ->toMediaCollection($collection);
                    }

                    $customer_payment =  \Modules\Customers\Entities\CustomerPaymentsDate::where('payment_id', $check->id)
                    ->where('payment_type', 'Modules\Customers\Entities\Check')->first();
                    $customer_payment->contract_number  = $receiptStatement->check_number ;
                    $customer_payment->employee_id  = $check->employee_id ;
                    $customer_payment->label  = 'إيصال قبض بواسطة شيك'. $check->check_number ;
                    $customer_payment->payment_id  = $check->id ;
                    $customer_payment->state  = 'تم_السداد' ;
                    $customer_payment->payment_type  = 'Modules\Customers\Entities\Check' ;
                    $customer_payment->amount  = $check->amount ;
                    $customer_payment->currency_id  = $check->currency_id ;
                    $customer_payment->due_date = $check->due_date;
                    $customer_payment->created_by = \Auth::user()->id;
                    $customer_payment->save();
                    
                }
            }else{
                $customer_payment =  \Modules\Customers\Entities\CustomerPaymentsDate::where('payment_id', $receiptStatement->id)
                ->where('payment_type', 'Modules\Customers\Entities\Check')->first();
                if($customer_payment){
                    $customer_payment->contract_number  =  $receiptStatement->transaction_number ;
                    $customer_payment->employee_id  = $receiptStatement->employee_id ;
                    $customer_payment->label  = 'إيصاال قبض'. $receiptStatement->transaction_number ;
                    $customer_payment->payment_id  = $receiptStatement->id ;
                    $customer_payment->state  = 'تم_السداد' ;
                    $customer_payment->payment_type  = 'Modules\Customers\Entities\ReceiptStatement' ;
                    $customer_payment->amount  = $receiptStatement->received_amount ;
                    $customer_payment->currency_id  = $receiptStatement->currency_id ;
                    $customer_payment->due_date = $receiptStatement->transaction_date;
                    $customer_payment->created_by = \Auth::user()->id;
                    $customer_payment->save();
                }else{

                    $newCustomer_payment = new \Modules\Customers\Entities\CustomerPaymentsDate;
                    $newCustomer_payment->contract_number  =  $receiptStatement->transaction_number ;
                    $newCustomer_payment->employee_id  = $receiptStatement->employee_id ;
                    $newCustomer_payment->label  = 'إيصاال قبض'. $receiptStatement->transaction_number ;
                    $newCustomer_payment->payment_id  = $receiptStatement->id ;
                    $newCustomer_payment->state  = 'تم_السداد' ;
                    $newCustomer_payment->payment_type  = 'Modules\Customers\Entities\ReceiptStatement' ;
                    $newCustomer_payment->amount  = $receiptStatement->received_amount ;
                    $newCustomer_payment->currency_id  = $receiptStatement->currency_id ;
                    $newCustomer_payment->due_date = $receiptStatement->transaction_date;
                    $newCustomer_payment->created_by = \Auth::user()->id;
                    $newCustomer_payment->save();
                }
            }
            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "receipt-statement_image";

                $receiptStatement->addMediaFromRequest('image[0]')
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
