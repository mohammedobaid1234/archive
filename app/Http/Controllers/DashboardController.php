<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller{
    
    public function index(){
        $users_count = \Modules\Users\Entities\User::count();
        $customers_count = \Modules\Customers\Entities\Customer::count();
        $employees_count = \Modules\Employees\Entities\Employee::count();

        $archives_count = \Modules\Customers\Entities\Contract::count();

        return view('dashboard', compact(['users_count', 'customers_count', 'employees_count', 'archives_count',]));
    }
}