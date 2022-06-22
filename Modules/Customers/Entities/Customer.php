<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Customer extends Model {
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'cm_customers';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    // protected $appends = ['last_login'];

    public function user(){
        return $this->morphOne(\Modules\Users\Entities\User::class, 'userable');
    }
    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }

    public function notes(){
        return $this->hasMany(\Modules\Customers\Entities\Note::class, 'customer_id');
    }

    public function province(){
        return $this->belongsTo(\Modules\Core\Entities\CountryProvince::class, 'province_id');
    }

    public function company(){
        return $this->hasOne(\Modules\Customers\Entities\Company::class, 'owner_id');
    }


    public function scopeWhereFullNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(full_name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
    }

    public function scopeWhereMobileNoLike($query, $mobile_no){
        return $query->where('mobile_no', 'like', ('%' . trim($mobile_no) . '%'));
    }

    
    public function _full_name(){
        return [
            'title' => 'إسم الشركة / المؤسسة / الفرد',
            'input' => 'input',
            'name' => 'full_name',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'full_name']
            ]
        ];
    }
    public function _address(){
        return [
            'title' => 'العنوان',
            'input' => 'input',
            'name' => 'address',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'address']
            ]
        ];
    }


    public function _type(){
        return [
            "rowIndex" => 1,
            'title' => 'نوع الحساب',
            'input' => 'select',
            'name' => 'type',
            'classes' => ['select2'],
            'data' => [
                'options_source' => 'customer_types'
            ],
            'required' => true,
            'operations' => [
                'show' => ['text' => 'type'],
            ]
        ];
    }

    public function _company_name(){
        return [
            'title' => 'اسم الشركة',
            'input' => 'input',
            'name' => 'company_name',
            'operations' => [
                'show' => ['text' => 'company.name'],
                'update' => ['text' => 'company.name']
            ]
        ];
    }

    public function _mobile_no(){
        return [
            'title' => 'رقم الجوال',
            'input' => 'input',
            'name' => 'mobile_no',
            'maxlength' => 10,
            'required' => true,
            'operations' => [
                'show' => ['text' => 'mobile_no'],
                'update' => ['text' => 'mobile_no']
            ]
        ];
    }

    public function _province(){
        return [
            'title' => 'المحافظة',
            'input' => 'select',
            'name' => 'province_id',
            'required' => true,
            'classes' => ['select2'],
            'data' => [
                'options_source' => 'provinces'
            ],
            'operations' => [
                'show' => ['text' => 'province.name', 'id' => 'province_id'],
                'update' => ['text' => 'province.name', 'id' => 'province_id']
            ]
        ];
    }

  
}
