<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleInvoice extends Model{
    use SoftDeletes;

    protected $table = 'pm_carts';

    public function product(){
        return $this->belongsToMany(\Modules\Products\Entities\Product::class);
    }
    public function cart(){
        return $this->hasMany(\Modules\Carts\Entities\Cart::class);
    }
    public function created_by_user(){
            return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }
    public function employee(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class, 'employee_id');
    }
    public function customer(){
        return $this->belongsTo(\Modules\Customers\Entities\Customer::class, 'customer_id');
    }
    
   
}
