<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorForCustomer extends Model{
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'cm_companies';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    protected $appends = [];

   

    public function employee(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class, 'customer_id');
    }

    public function customer(){
        return $this->belongsTo(\Modules\Customers\Entities\Customer::class, 'customer_id');
    }

    public function currency(){
        return $this->belongsTo(\Modules\Core\Entities\Currency::class, 'currency_id');
    }
}
