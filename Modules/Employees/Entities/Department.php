<?php

namespace Modules\Employees\Entities;

use Illuminate\Database\Eloquent\Model;

class Department extends Model{
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'em_employee_departments';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function scopeWhereLabelLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(label, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
    }
    public function _label(){
        return [
            'title' => ' الاسم بالعربي',
            'input' => 'input',
            'name' => 'label',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'label']
            ]
        ];
    }
    public function _name(){
        return [
            'title' => 'الاسم بالإنجليزي',
            'input' => 'input',
            'name' => 'name',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'name']
            ]
        ];
    }
}
