<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SaleInvoice extends Model implements HasMedia{
    use SoftDeletes;
    use InteractsWithMedia;
 
    protected $table = 'cm_sales_invoices';
    protected $appends = ['sale_invoice_image_url'];
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    protected $with=['employee:id,full_name','customer:id,full_name','created_by_user'];


    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400);
    }
    public function getSaleInvoiceImageUrlAttribute(){
        $image = $this->getMedia('sale_invoice_image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
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
    public function scopeWhereCreatedAt($query, $created_at){
        return $query->where(function($query) use ($created_at){
            if(str_contains(trim($created_at), ' - ')){
                $created_at = explode(' - ', $created_at);
                $created_at_from = $created_at[0];
                $created_at_from = $created_at[1];

                $query->whereDate('created_at', '>=', date('Y-m-d', strtotime(trim($created_at[0]))));
                $query->whereDate('created_at', '<=', date('Y-m-d', strtotime(trim($created_at[1]))));
            }else{
                $query->whereDate('created_at', date('Y-m-d', strtotime(trim($created_at))));
            }
        });
    }
    
   
}
