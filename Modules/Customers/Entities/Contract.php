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
    public function note(){
        return $this->hasOne(\Modules\Customers\Entities\Note::class, 'contract_id');
    }

    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }


    public function category_of_contract(){
        return $this->belongsTo(\Modules\Customers\Entities\CategoriesOfContracts::class, 'category_of_contract');
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
            'title' => 'تفاصيل أخرى', 
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
