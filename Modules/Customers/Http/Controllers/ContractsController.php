<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ContractsController extends Controller{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات العقود";
    private $model = \Modules\Customers\Entities\contract::class;

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
        \Auth::user()->authorize('contracts_module_contracts_manage');

        $data['activePage'] = ['contracts' => true];
        $data['breadcrumb'] = [
            ['title' => $this->title],
        ];

        return view('customers::contracts', $data);
    }

    public function datatable(Request $request){
        \Auth::user()->authorize('contracts_module_contracts_manage');

        $eloquent = $this->model::with(['customer', 'created_by_user', 'category_of_contract', 'note']);

        if ((int) $request->filters_status) {
            if (trim($request->id) !== "") {
                $eloquent->where('id', trim($request->id));
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
        }

        $filters = [
            ['title' => 'رقم الملف', 'type' => 'input', 'name' => 'id'],
            ['title' => 'اسم العميل', 'type' => 'select', 'name' => 'customer_id', 'data' => ['options_source' => 'customers', 'has_empty' => true]],
            ['title' => 'رقم جوال العميل', 'type' => 'input', 'name' => 'mobile_no'],
            ['title' => 'نوع العقد', 'type' => 'select', 'name' => 'category_of_contract', 'data' => ['options_source' => 'categories_of_contacts', 'has_empty' => true]],
        ];

        $columns = [
            ['title' => 'رقم الملف', 'column' => 'id'],
            ['title' => 'الاسم العميل', 'column' => 'customer.full_name'],
            ['title' => 'نوع العقد', 'column' => 'category_of_contract.name'],
            ['title' => 'تفاصيل أخرى ', 'column' => 'note.content'],
            ['title' => 'صورة العقد ', 'column' => 'contract_image_url',  'formatter' => 'contract_image'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'بواسطة', 'column' => 'created_by_user.name'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }

    public function store(Request $request){
        \Auth::user()->authorize('customers_module_customers_store');

        $request->validate([
            'customer_id' => 'required',
            'category_of_contract' => 'required',
            'content' => 'required|string',
        ]);
        $customer = \Modules\Customers\Entities\Customer::where('id', $request->customer_id)->first();
        if(!$customer){
            return response()->json(['message' => "يرجى التحقق من العميل."], 403);
        }

        $categories_of_contacts = \Modules\Customers\Entities\CategoriesOfContracts::where('id', $request->category_of_contract)->first();
        if(!$categories_of_contacts ){
            return response()->json(['message' => "يرجى التحقق من نوع العقد."], 403);
        }
        if(!trim($request->content)){
            return response()->json(['message' => "يرجى التحقق من التفاصيل ."], 403);
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

    public function show($id){
        return $this->model::with(['customer', 'created_by_user', 'category_of_contract', 'note'])->whereId($id)->first();
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
