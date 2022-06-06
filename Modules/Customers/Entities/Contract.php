<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Contract extends Model  implements HasMedia {
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;
    use InteractsWithMedia;

    protected $table = 'cm_contacts';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    protected $appends = ['contract_image_url'];
    
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media  $media = null): void{
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400);
    }
    public function getContractImageUrlAttribute(){
        $image = $this->getMedia('contract_image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/' . $image->file_name;
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
    }
    public function customer(){
        return $this->belongsTo(\Modules\Customers\Entities\Customer::class, 'customer_id');
    }
    public function product_for_user(){
        return $this->hasOne(\Modules\Customers\Entities\ProductForCustomer::class);
    }
    public function note(){
        return $this->hasOne(\Modules\Customers\Entities\Note::class, 'contract_id');
    }

    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
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
    public function category_of_contract(){
        return $this->belongsTo(\Modules\Customers\Entities\CategoriesOfContracts::class, 'category_of_contract');
    }
    
    public function scopeWhereStaredAt($query, $started_at){
        return $query->whereHas('product_for_user',function($query) use ($started_at){
            if(str_contains(trim($started_at), ' - ')){
                $started_at = explode(' - ', $started_at);
                $started_at_from = $started_at[0];
                $started_at_from = $started_at[1];

                $query->whereDate('contract_starting_date', '>=', date('Y-m-d', strtotime(trim($started_at[0]))));
                $query->whereDate('contract_starting_date', '<=', date('Y-m-d', strtotime(trim($started_at[1]))));
            }else{
                $query->whereDate('contract_starting_date', date('Y-m-d', strtotime(trim($started_at))));
            }
        });
    }
    public function scopeWhereEndedAt($query, $ended_at){
        return $query->whereHas('product_for_user',function($query) use ($ended_at){
            if(str_contains(trim($ended_at), ' - ')){
                $ended_at = explode(' - ', $ended_at);
                $ended_at_from = $ended_at[0];
                $ended_at_from = $ended_at[1];

                $query->whereDate('contract_ending_date', '>=', date('Y-m-d', strtotime(trim($ended_at[0]))));
                $query->whereDate('contract_ending_date', '<=', date('Y-m-d', strtotime(trim($ended_at[1]))));
            }else{
                $query->whereDate('contract_ending_date', date('Y-m-d', strtotime(trim($ended_at))));
            }
        });
    }

    public function _customer_id(){
        return [
            'title' => 'اسم العميل',
            'input' => 'select',
            'name' => 'customer_id',
            'classes' => ['select2'],
            'required' => true,
            'data' => [
                'options_source' => 'customers',
                'placeholder' => 'اسم العميل...'
            ],
            'operations' => [
                'show' => ['text' => 'customer.full_name', 'id' => 'customer_id'],
                'update' => ['active' => false]
            ]
        ];
    }

    public function _employee_id(){
        return [
            'title' => 'اسم الموظف المكلف',
            'input' => 'select',
            'name' => 'employee_id',
            'classes' => ['select2'],
            'required' => true,
            'data' => [
                'options_source' => 'employees',
                'placeholder' => 'اسم  الموظف المكلف...'
            ],
            'operations' => [
                'show' => ['text' => 'productForUser.employee.full_name', 'id' => 'employee_id'],
                'update' => ['active' => false]
            ]
        ];
    }
    public function _product_type(){
        return [
            'title' => 'نوع المولد', 
            'input' => 'input', 
            'name' => 'product_type', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'productForUser.product_type']
            ]
        ];
    }

    public function _product_model(){
        return [
            'title' => 'موديل المولد', 
            'input' => 'input', 
            'name' => 'product_model', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'productForUser.product_model']
            ]
        ];
    }
    public function _product_capacity(){
        return [
            'title' => 'قدرة المولد', 
            'input' => 'input', 
            'name' => 'product_capacity', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'productForUser.product_capacity']
            ]
        ];
    }
    public function _product_price(){
        return [
            'title' => 'سعر البيع', 
            'input' => 'input', 
            'name' => 'product_price', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'productForUser.product_price']
            ]
        ];
    }
    public function _currency_id(){
        return [
            'title' => 'نوع العملة',
            'input' => 'select',
            'name' => 'currency_id',
            'classes' => ['select2'],
            'required' => true,
            'data' => [
                'options_source' => 'currencies',
                'placeholder' => 'نوع العملة...'
            ],
            'operations' => [
                'show' => ['text' => 'currency_id.name', 'id' => 'currency_id'],
                'update' => ['text' => 'currency_id.name', 'id' => 'category_of_currencies']
            ]
        ];
    }
    public function _other_details(){
        return [
            'title' => 'تفاصيل أخرى للمولد', 
            'input' => 'textarea', 
            'name' => 'other_details', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'productForUser.other_details']
            ]
        ];
    }
    public function _contract_starting_date(){
        return [
            'title' => 'تاريخ بداية العقد', 
            'input' => 'input', 
            'date' => 'true',
            'name' => 'contract_starting_date', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'productForUser.contract_starting_date']
            ]
        ];
    }
    public function _contract_ending_date(){
        return [
            'title' => ' (للصيانة فقط)تاريخ نهاية العقد', 
            'input' => 'input', 
            'date' => 'true',
            'name' => 'contract_ending_date', 
            'operations' => [
                'show' => ['text' => 'productForUser.contract_ending_date']
            ]
        ];
    }
    
   
    
    public function _category_of_contract(){
        return [
            'title' => 'نوع العقد',
            'input' => 'select',
            'name' => 'category_of_contract',
            'classes' => ['select2'],
            'required' => true,
            'data' => [
                'options_source' => 'categories_of_contracts',
                'placeholder' => 'نوع العقد...'
            ],
            'operations' => [
                'show' => ['text' => 'category_of_contract.name', 'id' => 'category_of_contract'],
                'update' => ['text' => 'category_of_contract.name', 'id' => 'category_of_contract']
            ]
        ];
    }
    

    
    public function _content(){
        return [
            'title' => 'تفاصيل أخرى للعقد', 
            'input' => 'textarea', 
            'name' => 'content', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'note.content']
            ]
        ];
    }
    public function _image(){
        return [
            'title' => 'صورة العقد',
            'input' => 'input',
            'type' => 'file',
            'name' => 'image',
            'operations' => [
                'show' => ['text' => 'image'],
                'update' => ['text' => 'image'],
            ]
        ];
    }
  
}
