<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomersController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات العملاء";
    private $model = \Modules\Customers\Entities\Customer::class;

    public function index(Request $request){
        $response = $this->model::with([]);

        if (in_array('scopeActive', get_class_methods($this->model))) {
            $response->active();
        }

        $response->orderBy('created_at', 'DESC');

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
        \Auth::user()->authorize('customers_module_customers_manage');

        $data['activePage'] = ['customers' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('customers::customers', $data);
    }

    public function datatable(Request $request){
        \Auth::user()->authorize('customers_module_customers_manage');

        $eloquent = $this->model::with(['created_by_user', 'user', 'province']);

        if ((int) $request->filters_status) {
            if (trim($request->id) !== "") {
                $eloquent->where('id', trim($request->id));
            }
            if (trim($request->type) !== "") {
                $eloquent->where('type', trim($request->type));
            }
            if (trim($request->full_name) !== '') {
                $eloquent->whereFullNameLike($request->full_name);
            }
            if (trim($request->mobile_no) !== '') {
                $eloquent->whereMobileNoLike($request->mobile_no);
            }
            if (trim($request->province_id) !== "") {
                $eloquent->where('province_id', trim($request->province_id));
            }
        }

        $filters = [
            ['title' => 'رقم الملف', 'type' => 'input', 'name' => 'id'],
            ['title' => 'الاسم', 'type' => 'input', 'name' => 'full_name'],
            ['title' => 'رقم الجوال', 'type' => 'input', 'name' => 'mobile_no'],
            ['title' => 'النوع', 'type' => 'select', 'name' => 'type', 'data' => ['options_source' => 'customer_types', 'has_empty' => true]],
            ['title' => 'المحافظة', 'type' => 'select', 'name' => 'province_id', 'data' => ['options_source' => 'provinces', 'has_empty' => true]],
        ];

        $columns = [
            ['title' => 'رقم الملف', 'column' => 'id'],
            ['title' => 'الاسم بالكامل', 'column' => 'full_name', 'formatter' => 'customer'],
            ['title' => 'النوع', 'column' => 'type'],
            ['title' => 'رقم الجوال', 'column' => 'mobile_no'],
            ['title' => 'المحافظة', 'column' => 'province.name'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        \Auth::user()->authorize('customers_module_customers_store');

        $request->validate([
            'mobile_no' => 'required',
            'first_name' => 'required',
            'father_name' => 'required',
            'grandfather_name' => 'required',
            'last_name' => 'required',
            'type' => 'required',
        ]);

        if (trim($request->mobile_no) !== "") {
            if (strlen(trim($request->mobile_no)) !== 10) {
                return response()->json(['message' => "يرجى التحقق من صحة رقم الجوال."], 403);
            }

            if (\Modules\Customers\Entities\Customer::where('mobile_no', trim($request->mobile_no))->count()) {
                return response()->json(['message' => "لا يمكن تكرار رقم الجوال"], 403);
            }

            if (\Modules\Users\Entities\User::where('email', trim($request->mobile_no))->count()) {
                return response()->json(['message' => "لا يمكن تكرار رقم الجوال"], 403);
            }
        }

        if(trim($request->type) == "شركة" && trim($request->company_name) == ""){
            return response()->json(['message' => "يرجى التحقق من ادخال اسم الشركة."], 403);
        }

        \DB::beginTransaction();
        try {
            $customer = new \Modules\Customers\Entities\Customer;
            $customer->first_name = $request->first_name;
            $customer->father_name = $request->father_name;
            $customer->grandfather_name = $request->grandfather_name;
            $customer->last_name = $request->last_name;
            $customer->type = trim($request->type);
            $customer->mobile_no = trim($request->mobile_no);
            $customer->province_id = trim($request->province_id) ? trim($request->province_id) : NULL;
            $customer->address = trim($request->address) ? trim($request->address) : NULL;
            $customer->created_by = \Auth::user()->id;
            $customer->save();

            if(trim($request->type) == "شركة"){
                $company = new \Modules\Customers\Entities\Company;
                $company->owner_id = $customer->id;
                $company->name = trim($request->company_name);
                $company->created_by = \Auth::user()->id;
                $company->save();
            }

            $user = new \Modules\Users\Entities\User;
            $user->userable_id = $customer->id;
            $user->userable_type = "Modules\Customers\Entities\Customer";
            $user->email = $customer->mobile_no;
            $user->password = \Illuminate\Support\Facades\Hash::make(rand(1000000, 9999999));
            $user->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }

    public function show($id){
        return $this->model::with(['user', 'province', 'company'])->whereId($id)->first();
    }

    public function update(Request $request, $customer_id){
        \Auth::user()->authorize('customers_module_customers_update');
        $request->validate([
            'mobile_no' => 'required'
        ]);

        if (trim($request->mobile_no) !== "") {
            if (strlen(trim($request->mobile_no)) !== 10) {
                return response()->json(['message' => "يرجى التحقق من صحة رقم الجوال."], 403);
            }

            if (\Modules\Customers\Entities\Customer::where('id', '<>', $customer_id)->where('mobile_no', trim($request->mobile_no))->count()) {
                return response()->json(['message' => "لا يمكن تكرار رقم الجوال"], 403);
            }
        }

        if (trim($request->mobile_no) !== "") {
            if (\Modules\Users\Entities\User::where('userable_id', '<>', $customer_id)->where('email', trim($request->mobile_no))->count()) {
                return response()->json(['message' => "لا يمكن تكرار رقم الجوال"], 403);
            }
        }

        if(trim($request->type) == "شركة" && trim($request->company_name) == ""){
            return response()->json(['message' => "يرجى التحقق من ادخال اسم الشركة."], 403);
        }

        \DB::beginTransaction();
        try {

            $customer = \Modules\Customers\Entities\Customer::whereId($customer_id)->first();
            $customer->type = trim($request->type);
            $customer->mobile_no = trim($request->mobile_no);
            $customer->province_id = trim($request->province_id) ? trim($request->province_id) : NULL;
            $customer->address = trim($request->address) ? trim($request->address) : NULL;

            $customer->save();
            
            $company = \Modules\Customers\Entities\Company::where('owner_id', $customer->id)->first();

            if($company){
                $company->name = trim($request->company_name);
                $company->save();
            }

            $user = \Modules\Users\Entities\User::where('userable_type', 'Modules\Customers\Entities\Customer')->where('userable_id', $customer_id)->first();

            if (!$user) {
                $user = new \Modules\Users\Entities\User;
                $user->userable_id = $customer->id;
                $user->userable_type = "Modules\Customers\Entities\Customer";
                $user->password = \Illuminate\Support\Facades\Hash::make(rand(1000000, 9999999));
                $user->created_by = \Auth::user()->id;
            }

            $user->email = $customer->mobile_no;
            $user->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'ok']);
    }

}
