<?php

namespace Modules\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Contacts extends Model {
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'cm_contacts';

    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];

    public function customer(){
        return $this->belongsTo(\Modules\Employees\Entities\Employee::class, 'customer_id');
    }

    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }


    public function categories_of_contacts(){
        return $this->belongsTo(\Modules\Customers\Entities\CategoriesOfContracts::class, 'categories_of_contacts');
    }

    
    public function _categories_of_contacts(){
        return [
            'title' => 'نوع العقد',
            'input' => 'select',
            'name' => 'categories_of_contacts',
            'classes' => ['select2'],
            'required' => true,
            'data' => [
                'options_source' => 'categories_of_contacts',
                'placeholder' => 'نوع العقد...'
            ],
            'operations' => [
                'show' => ['text' => 'categories_of_contacts', 'id' => 'categories_of_contacts'],
                'update' => ['active' => false]
            ]
        ];
    }
    public function _customer_id(){
        return [
            'title' => 'اسم العميل',
            'input' => 'select',
            'name' => 'customer_id',
            'classes' => ['select2'],
            'required' => true,
            'data' => [
                'options_source' => 'customer_id',
                'placeholder' => 'اسم العميل...'
            ],
            'operations' => [
                'show' => ['text' => 'name', 'id' => 'customer_id'],
                'update' => ['active' => false]
            ]
        ];
    }
  
}
