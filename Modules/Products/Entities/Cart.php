<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model{
    use SoftDeletes;
    protected $table = 'cm_sales_invoices';

    public function product(){
        return $this->belongsTo(\Modules\Products\Entities\Product::class);
    }
    public function sale_invoice(){
        return $this->belongsTo(\Modules\Customers\Entities\SaleInvoice::class);
    }
}
