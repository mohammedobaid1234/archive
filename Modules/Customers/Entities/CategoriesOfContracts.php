<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;

class CategoriesOfContracts extends Model {
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'cm_categories_of_contacts';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];


    public function scopeWhereFullNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
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
      
}
