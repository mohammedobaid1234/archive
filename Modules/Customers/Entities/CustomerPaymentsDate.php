<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPaymentsDate extends Model{
    use SoftDeletes;
    
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'cm_customer_payments_dates';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }
    public function employee(){
        return $this->belongsTo(\Modules\Customers\Entities\Employee::class);
    }
    public function contract(){
        return $this->belongsTo(\Modules\Customers\Entities\Contract::class);
    }
}
