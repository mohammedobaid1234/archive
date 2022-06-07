<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model{
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;
    
    protected $table = 'core_banks';
    
    public function scopeWhereNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
    }

    public function scopeWhereAddressLike($query, $address){
        $address = str_replace("أ", "ا", $address);
        $address = str_replace("إ", "ا", $address);
        $address = str_replace("ة", "ه", $address);
        $address = str_replace("ى", "ي", $address);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE("address", "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($address) . '%'));
    }

    public function scopeWhereMobileNumberLike($query, $mobile_number){
        return $query->where('mobile_number', 'like', ('%' . trim($mobile_number) . '%'));
    }
    

    public function _name(){
        return [
            'title' => 'الاسم', 
            'input' => 'input', 
            'name' => 'name', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'name']
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
    public function _mobile_number(){
        return [
            'title' => 'رقم الهاتف', 
            'input' => 'input', 
            'name' => 'mobile_number', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'mobile_number']
            ]
        ];
    }
}
