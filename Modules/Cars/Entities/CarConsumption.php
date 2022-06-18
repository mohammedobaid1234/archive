<?php

namespace Modules\Cars\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class carConsumption extends Model  implements HasMedia{
    use \Modules\BriskCore\Traits\ModelTrait;
    use InteractsWithMedia;

    protected $table = 'cr_cars_consumption';
    protected $appends = ['invoice_image_url'];

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function car(){
        return $this->belongsTo(\Modules\Cars\Entities\Car::class);
    }
    public function getInvoiceImageUrlAttribute(){
        $image = $this->getMedia('invoice-car-consumption-image')->first();

        if($image){
            return url('/') . '/storage/app/public/' . $image->id . '/conversions/' . $image->file_name;
        }

        return asset('/public/themes/Falcon/v2.8.0/assets/img/team/avatar.png');
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
    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }
    public function driver(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class,'driver_id');
    }

    public function _car_id(){
        return [
            'title' => 'اسم السيارة  ',
            'input' => 'select',
            'name' => 'car_id',
            'classes' => ['select2'],
            'required' => true,
            'data' => [
                'options_source' => 'cars',
            ],
            'operations' => [
                'show' => ['text' => 'car.type', 'id' => 'car.id'],
            ]
        ];
    }
    public function _quantity(){
        return [
            'title' => '(اللتر)الكمية',
            'input' => 'input',
            'name' => 'quantity',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'quantity']
            ]
        ];
    }
    public function _amount(){
        return [
            'title' => '(الشيكل)قيمة',
            'input' => 'input',
            'name' => 'amount',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'amount']
            ]
        ];
    }
    public function _note(){
       return [
        'title' => 'تفاصيل أخرى ',
        'input' => 'textarea', 
        'name' => 'note', 
        'placeholder' => '  تفاصيل أخرى  ...'
        ,'operations' => ['show' => ['text' => 'note']]
       ];

    }
    public function _image(){
        return [
            'title' => 'صورة الفاتورة',
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
