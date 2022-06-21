<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DraftsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات الكمبيالات";
    private $model = \Modules\Customers\Entities\Draft::class;


    public function manage(){
        \Auth::user()->authorize('customers_module_drafts_manage');

        $data['activePage'] = ['drafts' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('customers::drafts', $data);
    }
    public function datatable(Request $request){
        \Auth::user()->authorize('customers_module_drafts_manage');

        $eloquent = $this->model::with(['customer', 'created_by_user', 'currency','employee']);

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
            if (trim($request->draft_number) !== "") {
                $eloquent->where('draft_number', 'LIKE', "%".trim($request->draft_number).'%');
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
        }

        $filters = [
            ['title' => 'اسم العميل', 'type' => 'select', 'name' => 'customer_id', 'data' => ['options_source' => 'customers', 'has_empty' => true]],
            ['title' => 'رقم جوال العميل', 'type' => 'input', 'name' => 'mobile_no'],
            ['title' => 'اسم الموظف المسؤول', 'type' => 'select', 'name' => 'employee_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
            ['title' =>  ' تاريخ إستحقاق الكمبيالة', 'type' => 'input', 'name' => 'due_date', 'date_range' => true],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'الاسم العميل ', 'column' => 'customer.full_name','formatter' => 'customerProfile' ],
            ['title' => 'اسم الموظف المسؤول ', 'column' => 'employee.full_name'],
            ['title' => 'رقم هوية العميل ', 'column' => 'national_id' ],
            ['title' => 'عنوان العميل ', 'column' => 'address' ],
            ['title' => 'قيمة الكمبيالة ', 'column' => 'amount'],
            ['title' => 'نوع العملة', 'column' => 'currency.name'],
            ['title' => 'اسم  الكفيل', 'column' => 'sponsor_name'],
            ['title' => 'اسم الشاهد الأول', 'column' => 'watch_first'],
            ['title' => 'اسم الشاهد الثاني', 'column' => 'watch_second'],
            ['title' => ' تاريخ صرف الكمبيالة', 'column' => 'due_date'],
            ['title' => "تفاصيل أخرى", 'column' => 'additional_details','formatter' => 'additionalDetails'],
            ['title' => "صورة الكمبيالة", 'column' => 'image','formatter' => 'image'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
    public function store(Request $request){
        
        \Auth::user()->authorize('customers_module_draft_store');

        $request->validate([
            'customer_id' => 'required',
            'employee_id' => 'required',
            'amount' => 'required',
            'currency_id' => 'required',
            'due_date' => 'required',
            'address' => 'required',
            'image' => 'required',
            'sponsor_name' => 'required',
            'national_id' => 'required',
            'watch_first' => 'required',
            'watch_second' => 'required',
            'additional_details' => 'required',
        ]);
        $customer = \Modules\Customers\Entities\Customer::where('id', $request->customer_id)->first();
        if(!$customer){
            return response()->json(['message' => "يرجى التحقق من العميل."], 403);
        }
       
        if(!$request->due_date){
            return response()->json(['message' => "يرجى إضافة تاريخ إستحقاق الكمبيالة ."], 403);
        }
        if($request->due_date < now()){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
        }

        \DB::beginTransaction();
        try {
            $draft = new \Modules\Customers\Entities\Draft;
            $draft->employee_id = $request->employee_id;
            $draft->customer_id = $request->customer_id;
            $draft->amount = $request->amount;
            $draft->currency_id = $request->currency_id;
            $draft->sponsor_name = $request->sponsor_name;
            $draft->national_id = $request->national_id;
            $draft->due_date = $request->due_date;
            $draft->address = $request->address;
            $draft->watch_first = $request->watch_first;
            $draft->watch_second = $request->watch_second;
            $draft->due_date = $request->due_date;
            $draft->additional_details = $request->additional_details;
            $draft->created_by = \Auth::user()->id;
            $draft->save();
            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "draft_image";

                $draft->addMediaFromRequest('image[0]')
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
                ['title' => 'اسم الموظف المسؤول  ', 'input' => 'select', 'name' => 'employee_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'employees', 'placeholder' => 'اسم الموظفين...'],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee_id']]],
                ['title' => 'رقم هوية العميل ', 'input' => 'input', 'name' => 'national_id', 'required' => true,'operations' => ['show' => ['text' => 'national_id']]],
                ['title' => 'اسم المستفيد  ', 'input' => 'select', 'name' => 'customer_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'customers', 'placeholder' => 'اسم العميل...'],'operations' => ['show' => ['text' => 'customer.full_name', 'id' => 'customer_id']]],
                [
                    ['title' => 'قيمة الكمبيالة ', 'input' => 'input', 'name' => 'amount',  'classes' => ['numeric'],'required' => true,'operations' => ['show' => ['text' => 'amount']]],
                    ['title' => 'نوع العملة  ', 'input' => 'select', 'name' => 'currency_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'currencies', ],'operations' => ['show' => ['text' => 'currency.name', 'id' => 'currency.id']]],

                ],
                ['title' => 'عنوان العميل ', 'input' => 'input', 'name' => 'address', 'required' => true,'operations' => ['show' => ['text' => 'address']]],
                [
                    ['title' => 'اسم الكفيل ', 'input' => 'input', 'name' => 'sponsor_name', 'required' => true,'operations' => ['show' => ['text' => 'sponsor_name']]],
                    ['title' => 'اسم الشاهد الأول ', 'input' => 'input', 'name' => 'watch_first', 'required' => true,'operations' => ['show' => ['text' => 'watch_first']]],
                    ['title' => 'اسم الشاهد الثاني ', 'input' => 'input', 'name' => 'watch_second', 'required' => true,'operations' => ['show' => ['text' => 'watch_second']]],
                ],
                ['title' => 'تاريخ الاستحقاق الكمبيالة ', 'input' => 'input', 'name' => 'due_date', 'classes' => ['numeric'], 'date' => true,'operations' => ['show' => ['text' => 'due_date']]],
                ['title' => 'تفاصيل إضافية ...', 'input' => 'textarea', 'name' => 'additional_details',  'placeholder' => 'تفاصيل إضافية ...','operations' => ['show' => ['text' => 'additional_details']]],

                ['title' => 'صورة الكمبيالة', 'input' => 'input','type' => 'file', 'name' => 'image','operations' => ['show' => ['text' => 'image'],'update' => ['text' => 'image'],]]
            ]
        ];
    }
    public function show($id){
        return $this->model::with(['customer', 'created_by_user','currency','employee'])->whereId($id)->first();
    }
    public function update(Request $request, $id){
        
        \Auth::user()->authorize('customers_module_draft_update');

        $request->validate([
            'customer_id' => 'required',
            'employee_id' => 'required',
            'amount' => 'required',
            'currency_id' => 'required',
            'due_date' => 'required',
            'address' => 'required',
            'sponsor_name' => 'required',
            'national_id' => 'required',
            'watch_first' => 'required',
            'watch_second' => 'required',
            'additional_details' => 'required',
        ]);
        $customer = \Modules\Customers\Entities\Customer::where('id', $request->customer_id)->first();
        if(!$customer){
            return response()->json(['message' => "يرجى التحقق من العميل."], 403);
        }
       
        if(!$request->due_date){
            return response()->json(['message' => "يرجى إضافة تاريخ إستحقاق الكمبيالة ."], 403);
        }
        if($request->due_date < now()){
            return response()->json(['message' => "التاريخ المدخل غير منطقي ."], 403);
        }

        \DB::beginTransaction();
        try {
            $draft =  \Modules\Customers\Entities\Draft::whereId($id)->first();
            $draft->employee_id = $request->employee_id;
            $draft->customer_id = $request->customer_id;
            $draft->amount = $request->amount;
            $draft->currency_id = $request->currency_id;
            $draft->sponsor_name = $request->sponsor_name;
            $draft->national_id = $request->national_id;
            $draft->due_date = $request->due_date;
            $draft->address = $request->address;
            $draft->watch_first = $request->watch_first;
            $draft->watch_second = $request->watch_second;
            $draft->due_date = $request->due_date;
            $draft->additional_details = $request->additional_details;
            $draft->created_by = \Auth::user()->id;
            $draft->save();
            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "draft_image";

                $draft->addMediaFromRequest('image[0]')
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
