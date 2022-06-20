<?php

namespace Modules\Expenses\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExpensesController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات المصروفات ";
    private $model = \Modules\Expenses\Entities\Expense::class;


    public function manage(){
        \Auth::user()->authorize('expenses_module_expenses_manage');

        $data['activePage'] = ['expenses' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('expenses::expenses', $data);
    }
     public function datatable(Request $request){
        
        \Auth::user()->authorize('expenses_module_expensess_manage');

        $eloquent = $this->model::with(['employee', 'created_by_user','customer','currency_for_total','currency_for_rest']);

        if ((int) $request->filters_status) {
            if (trim($request->employee_id) !== '') {
                $eloquent->whereHas('employee', function($query) use ($request){
                    $query->where('id', trim($request->employee_id));
                });
            }
            if (trim($request->customer_id) !== '') {
                $eloquent->whereHas('customer', function($query) use ($request){
                    $query->where('id', trim($request->customer_id));
                });
            }
         
            if (trim($request->note) !== "") {
                $eloquent->where('note', 'LIKE', "%".trim($request->note).'%');
            }
            if (trim($request->transaction_number) !== "") {
                $eloquent->where('transaction_number', 'LIKE', "%".trim($request->transaction_number).'%');
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
          
        }

        $filters = [
            ['title' => ' رقم السند', 'type' => 'input', 'name' => 'transaction_number'],
            ['title' => ' المصدر ', 'type' => 'select', 'name' => 'customer_id', 'data' => ['options_source' => 'customers', 'has_empty' => true]],
            ['title' => 'اسم الموظف المسؤول ', 'type' => 'select', 'name' => 'employee_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
            ['title' => ' تفاصيل إضافية ', 'type' => 'input', 'name' => 'note'],
            
        ];

        $columns = [
            ['title' => 'رقم السند ', 'column' => 'transaction_number'],
            ['title' => 'الموظف ', 'column' => 'employee.full_name'],
            ['title' => 'المصدر', 'column' => 'customer.full_name'],
            ['title' => 'الإجمالي', 'column' => 'total'],
            ['title' => 'نوع العملة', 'column' => 'currency_for_total.name'],
            ['title' => 'المتبقي', 'column' => 'rest'],
            ['title' => 'نوع العملة', 'column' => 'currency_for_rest.name'],
            ['title' => 'تفاصيل إضافية ', 'column' => 'note','formatter' => 'notes' ],
            ['title' => "صورة سند القبض", 'column' => 'image','formatter' => 'image'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function create(){
        return [
            "title" => "اضافة سند قبض جديد",
            "inputs" => [
                ['title' => 'رقم السند ', 'input' => 'input', 'name' => 'transaction_number', 'operations' => ['show' => ['text' => 'transaction_number']]],
                ['title' => 'اسم المصدر', 'input' => 'select', 'name' => 'customer_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'customers', 'placeholder' => 'اسم الزبون (المصدر)...'],'operations' => ['show' => ['text' => 'customer.full_name', 'id' => 'customer_id']]],
                ['title' => 'اسم الموظف', 'input' => 'select', 'name' => 'employee_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'employees', 'placeholder' => 'اسم الموظف...'],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee_id']]],
                ['title' => 'الإجمالي ', 'input' => 'input', 'name' => 'total',  'classes' => ['numeric'],'required' => true,'operations' => ['show' => ['text' => 'total']]],
                ['title' => 'نوع العملة ', 'input' => 'select', 'name' => 'currency_id_of_total',  'classes' => ['select2'],'required' => true,'data' => ['options_source' => 'currencies', 'placeholder' => 'نوع العملة  (الإجمالي)...'],'operations' => ['show' => ['text' => 'currency_for_total.name', 'id' => 'currency_for_total.id']]],
                ['title' => 'المتبقي ', 'input' => 'input', 'name' => 'rest',  'required' => true,'operations' => ['show' => ['text' => 'rest']]],
                ['title' => 'نوع العملة ', 'input' => 'select', 'name' => 'currency_id_of_rest',  'classes' => ['select2'],'required' => true,'data' => ['options_source' => 'currencies', 'placeholder' => 'نوع العملة  (المتبقي)...'],'operations' => ['show' => ['text' => 'currency_for_rest.name', 'id' => 'currency_for_rest.id']]],

                ['title' => 'تفاصيل أخرى ...', 'input' => 'textarea', 'name' => 'note', 'operations' => ['show' => ['text' => 'note']]],
                ['title' => 'صورة سند القبض', 'input' => 'input','type' => 'file', 'name' => 'image','operations' => ['show' => ['text' => 'image'],'update' => ['text' => 'image'],]]
            ]
        ];
    }

    public function store(Request $request){
        
        \Auth::user()->authorize('expenses_module_expensess_store');

        $request->validate([
            'transaction_number' => 'required',
            'employee_id' => 'required',
            'customer_id' => 'required',
            'total' => 'required',
            'currency_id_of_total' => 'required',
            'rest' => 'required',
            'currency_id_of_rest' => 'required',
            'note' => 'required',
          
        ]);
        $employee = \Modules\Employees\Entities\Employee::where('id', $request->employee_id)->first();
        if(!$employee){
            return response()->json(['message' => "يرجى التحقق من الموظف."], 403);
        }
       
        \DB::beginTransaction();
        try {
            $expenses = new \Modules\Expenses\Entities\Expense;
            $expenses->transaction_number  = $request->transaction_number ;
            $expenses->customer_id  = $request->customer_id ;
            $expenses->employee_id = $request->employee_id;
            $expenses->total = $request->total;
            $expenses->currency_id_of_total = $request->currency_id_of_total;
            $expenses->rest = $request->rest;
            $expenses->currency_id_of_rest = $request->currency_id_of_rest;
            $expenses->note = $request->note;
            $expenses->created_by = \Auth::user()->id;
            $expenses->save();
            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "expense_image";

                $expenses->addMediaFromRequest('image[0]')
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

    public function show($id){
        return  $this->model::with(['employee', 'created_by_user','customer','currency_for_total','currency_for_rest'])->whereId($id)->first();
    }

    public function update(Request $request, $id){
        
        \Auth::user()->authorize('expenses_module_expensess_update');

        $request->validate([
            'transaction_number' => 'required',
            'employee_id' => 'required',
            'customer_id' => 'required',
            'total' => 'required',
            'currency_id_of_total' => 'required',
            'rest' => 'required',
            'currency_id_of_rest' => 'required',
            'note' => 'required',
          
        ]);
        $employee = \Modules\Employees\Entities\Employee::where('id', $request->employee_id)->first();
        if(!$employee){
            return response()->json(['message' => "يرجى التحقق من الموظف."], 403);
        }
       
        \DB::beginTransaction();
        try {
            $expenses =  \Modules\Expenses\Entities\Expense::whereId($id)->first();
            $expenses->transaction_number  = $request->transaction_number ;
            $expenses->customer_id  = $request->customer_id ;
            $expenses->employee_id = $request->employee_id;
            $expenses->total = $request->total;
            $expenses->currency_id_of_total = $request->currency_id_of_total;
            $expenses->rest = $request->rest;
            $expenses->currency_id_of_rest = $request->currency_id_of_rest;
            $expenses->note = $request->note;
            $expenses->created_by = \Auth::user()->id;
            $expenses->save();
            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "expense_image";

                $expenses->addMediaFromRequest('image[0]')
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
