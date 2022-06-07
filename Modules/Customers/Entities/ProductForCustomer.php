<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductForCustomer extends Model{
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'cm_products_for_customers';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    
    protected $with=['employee:id,full_name','customer:id,full_name','currency:id,name'];

   

    public function employee(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class, 'employee_id');
    }

    public function customer(){
        return $this->belongsTo(\Modules\Customers\Entities\Customer::class, 'customer_id');
    }

    public function currency(){
        return $this->belongsTo(\Modules\Core\Entities\Currency::class, 'currency_id');
    }
}
