<?php

namespace Modules\Customers\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProfileController extends Controller{

    public function index($customer_id){

        \Auth::user()->authorize('customers_module_customers_manage');

        $customer = \Modules\Customers\Entities\Customer::where('id', $customer_id)->first();

        if(!$customer){
            return view('errors.404');
        }

        $data['activePage'] = ['customers' => true];
        $data['customer'] = $customer;

        return view('customers::profile', $data);
    }
}
