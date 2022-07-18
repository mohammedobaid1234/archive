<?php

namespace Modules\Expenses\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExchangeBondController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات سندات الصرف";
    private $model = \Modules\Expenses\Entities\ExchangeBond::class;


    public function manage(){
        \Auth::user()->authorize('expenses_module_exchange_bonds_manage');

        $data['activePage'] = ['archive' => 'exchange_bonds'];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('expenses::exchange_bonds', $data);
    }
    public function datatable(Request $request){
        
        \Auth::user()->authorize('expenses_module_exchange_bonds_manage');

        $eloquent = $this->model::with(['employee', 'created_by_user',]);

        if ((int) $request->filters_status) {
            if (trim($request->employee_id) !== '') {
                $eloquent->whereHas('employee', function($query) use ($request){
                    $query->where('id', trim($request->employee_id));
                });
            }
         
            if (trim($request->reasons) !== "") {
                $eloquent->where('reasons', 'LIKE', "%".trim($request->reasons).'%');
            }
            if (trim($request->type_of_exchange_bonds) !== "") {
                $eloquent->where('type', 'LIKE', "%".trim($request->type_of_exchange_bonds).'%');
            }
            if (trim($request->product) !== "") {
                $eloquent->where('product', 'LIKE', "%".trim($request->product).'%');
            }
            if (trim($request->bond_no) !== "") {
                $eloquent->where('bond_no', 'LIKE', "%".trim($request->bond_no).'%');
            }
            if (trim($request->created_at) !== "") {
                $eloquent->whereCreatedAt($request->created_at);
            }
          
        }

        $filters = [
            ['title' => ' سند الصرف', 'type' => 'input', 'name' => 'bond_no'],
            ['title' => ' المصدر ', 'type' => 'select', 'name' => 'type_of_exchange_bonds', 'data' => ['options_source' => 'type_of_exchange_bonds', 'has_empty' => true]],
            ['title' => 'اسم الموظف ', 'type' => 'select', 'name' => 'employee_id', 'data' => ['options_source' => 'employees', 'has_empty' => true]],
            ['title' =>  '  تاريخ الإنشاء', 'type' => 'input', 'name' => 'created_at', 'date_range' => true],
            ['title' => ' سبب الشراء', 'type' => 'input', 'name' => 'reasons'],
            ['title' => ' المنتج ', 'type' => 'input', 'name' => 'product'],
            
        ];

        $columns = [
            ['title' => 'سند الصرف ', 'column' => 'bond_no'],
            ['title' => 'الموظف ', 'column' => 'employee.full_name'],
            ['title' => 'المصدر', 'column' => 'type'],
            ['title' => 'اسم المنتج ', 'column' => 'product'],
            ['title' => 'القيمة', 'column' => 'amount'],
            ['title' => 'سبب الشراء', 'column' => 'reasons','formatter' => 'reason' ],
            ['title' => "صورة سند الصرف", 'column' => 'image','formatter' => 'image'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function create(){
        return [
            "title" => "اضافة سند الصرف جديد",
            "inputs" => [
                ['title' => 'رقم السند ', 'input' => 'input', 'name' => 'bond_no', 'operations' => ['show' => ['text' => 'bond_no']]],
                ['title' => 'اسم المنتج ', 'input' => 'input', 'name' => 'product', 'operations' => ['show' => ['text' => 'product']]],
                ['title' => 'اسم الموظف', 'input' => 'select', 'name' => 'employee_id', 'required' => true,'classes' => ['select2'], 'data' => ['options_source' => 'employees', 'placeholder' => 'اسم الموظف...'],'operations' => ['show' => ['text' => 'employee.full_name', 'id' => 'employee_id']]],
                
                ['title' => ' اسم المصدر', 'input' => 'select', 'name' => 'type',  'classes' => ['select2'], 'data' => ['options_source' => 'type_of_exchange_bonds',],'operations' => ['show' => ['text' => 'type', 'id' => 'type'],'update' => ['text' => 'type', 'id' => 'type']]],
                ['title' => 'قيمة الشراء ', 'input' => 'input', 'name' => 'amount',  'classes' => ['numeric'],'required' => true,'operations' => ['show' => ['text' => 'amount']]],
                
                
                ['title' => 'سبب الشراء ...', 'input' => 'textarea', 'name' => 'reasons',  'placeholder' => 'سبب الشراء ...','operations' => ['show' => ['text' => 'reasons']]],

                ['title' => 'صورة سند صرف', 'input' => 'input','type' => 'file', 'name' => 'image','operations' => ['show' => ['text' => 'image'],'update' => ['text' => 'image'],]]
            ]
        ];
    }
    public function store(Request $request){
        
        \Auth::user()->authorize('customers_module_exchange_bond_store');

        $request->validate([
            'bond_no' => 'required',
            'employee_id' => 'required',
            'type' => 'required',
            'product' => 'required',
            'reasons' => 'required',
            'amount' => 'required',
          
        ]);
         $bond_no = \Modules\Expenses\Entities\ExchangeBond::where('id', $request->bond_no)->first();

        if($bond_no){
            return response()->json(['message' => "رقم سند القبض موجود مسبقا."], 403);
        }
        $employee = \Modules\Employees\Entities\Employee::where('id', $request->employee_id)->first();
        if(!$employee){
            return response()->json(['message' => "يرجى التحقق من الموظف."], 403);
        }
       
        \DB::beginTransaction();
        try {
            $exchange_bond = new \Modules\Expenses\Entities\ExchangeBond;
            $exchange_bond->bond_no  = $request->bond_no ;
            $exchange_bond->employee_id  = $request->employee_id ;
            $exchange_bond->type = $request->type;
            $exchange_bond->product = $request->product;
            $exchange_bond->reasons = $request->reasons;
            $exchange_bond->amount = $request->amount;
            $exchange_bond->created_by = \Auth::user()->id;
            $exchange_bond->save();
            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "exchange_bond_image";

                $exchange_bond->addMediaFromRequest('image[0]')
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
        return $this->model::with(['employee', 'created_by_user',])->whereId($id)->first();
    }

    public function update(Request $request, $id){
        
        \Auth::user()->authorize('customers_module_exchange_bond_update');

        $request->validate([
            'bond_no' => 'required',
            'employee_id' => 'required',
            'type' => 'required',
            'product' => 'required',
            'reasons' => 'required',
            'amount' => 'required',
          
        ]);
         $bond_no = \Modules\Expenses\Entities\ExchangeBond::where('id', '<>', $id)->where('id', $request->bond_no)->first();

        if($bond_no){
            return response()->json(['message' => "رقم سند القبض موجود مسبقا."], 403);
        }
        $employee = \Modules\Employees\Entities\Employee::where('id', $request->employee_id)->first();
        if(!$employee){
            return response()->json(['message' => "يرجى التحقق من الموظف."], 403);
        }
       
        \DB::beginTransaction();
        try {
            $exchange_bond =  \Modules\Expenses\Entities\ExchangeBond::whereId($id)->first();
            $exchange_bond->bond_no  = $request->bond_no ;
            $exchange_bond->employee_id  = $request->employee_id ;
            $exchange_bond->type = $request->type;
            $exchange_bond->product = $request->product;
            $exchange_bond->reasons = $request->reasons;
            $exchange_bond->amount = $request->amount;
            $exchange_bond->created_by = \Auth::user()->id;
            $exchange_bond->save();
            
            if($request->hasFile('image') && $request->file('image')[0]->isValid()){
                $extension = strtolower($request->file('image')[0]->extension());
                $media_new_name = strtolower(md5(time())) . "." . $extension;
                $collection = "exchange_bond_image";

                $exchange_bond->addMediaFromRequest('image[0]')
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
