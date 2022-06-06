<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductsForCustomerController extends Controller
{
    use \Modules\BriskCore\Traits\ResourceTrait;
    use \Modules\BriskCore\Traits\FormRequestTrait;

    private $title = "ملفات العقود";
    private $model = \Modules\Customers\Entities\contract::class;

    public function datatable(Request $request, $customer_id){
        \Auth::user()->authorize('customers_module_products_for_customer_manage');

        $eloquent = $this->model::with(['customer', 'created_by_user', 'category_of_contract', 'note','product_for_user'])->whereId($customer_id);

        if ((int) $request->filters_status) {
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
            
        }

        $filters = [
            ['title' => ' نوع المولد', 'type' => 'input', 'name' => 'product_type'],
            ['title' => ' موديل المولد', 'type' => 'input', 'name' => 'product_model'],
            ['title' => ' سعة المولد', 'type' => 'input', 'name' => 'product_capacity'],
            ['title' =>  ' تاريخ بدء العقد', 'type' => 'input', 'name' => 'contract_starting_date', 'date_range' => true],
            ['title' =>  ' تاريخ نهاية العقد', 'type' => 'input', 'name' => 'contract_ending_date', 'date_range' => true],
        ];

        $columns = [
            ['title' => 'الرقم التسلسلي', 'column' => 'product_for_user.serial_number'],
            ['title' => ' نوع المولد', 'column' => 'product_for_user.product_type'],
            ['title' => ' موديل المولد', 'column' => 'product_for_user.product_model'],
            ['title' => ' سعة المولد', 'column' => 'product_for_user.product_capacity'],
            ['title' => ' سعر البيع', 'column' => 'product_for_user.product_price'],
            ['title' => ' نوع العملة', 'column' => 'product_for_user.currency.name'],
            ['title' => ' تاريخ بدء العقد', 'column' => 'product_for_user.contract_starting_date'],
            ['title' => ' تاريخ نهاية العقد', 'column' => 'product_for_user.contract_ending_date'],
            ['title' => 'صورة العقد ', 'column' => 'contract_image_url',  'formatter' => 'contract_image'],
            ['title' => 'بيانات إضافية للمولد', 'column' => 'product_for_user.other_details',  'formatter' => 'contentForProduct'],
            ['title' => 'تاريخ الإنشاء', 'column' => 'created_at'],
            ['title' => 'الإجراءات', 'column' => 'operations', 'formatter' => 'operations']
        ];

        return response()->json($this->datatableInitializer($request, $eloquent, $filters, $columns));
    }
}
