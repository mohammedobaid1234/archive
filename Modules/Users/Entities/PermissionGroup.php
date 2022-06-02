<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model {
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'permission_groups';

    public $timestamps = false;

    protected $appends = ['name'];
    
    public function permissions(){
        return $this->hasMany(\Spatie\Permission\Models\Permission::class, 'group_id');
    }
    
    public function childrenGroups(){
        return $this->hasMany(\Modules\Users\Entities\PermissionGroup::class, 'parent_id')->orderByOrderNo();
    }
    
    public function allChildrenGroups(){
        return $this->childrenGroups()->with(['allChildrenGroups', 'permissions']);
    }

    public function getNameAttribute(){
        return $this->name_ar;
    }

    public function scopeOrderByOrderNo($query){
        return $query->orderByRaw("ISNULL(order_no) ASC, order_no ASC");
    }

    public function _name_en(){
        return [
            "rowIndex" => 1, 
            'title' => 'الاسم (en)', 
            'input' => 'input', 
            'name' => 'name_en', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'name_en']
            ]
        ];
    }

    public function _name_ar(){
        return [
            "rowIndex" => 2, 
            'title' => 'الاسم (ع)', 
            'input' => 'input', 
            'name' => 'name_ar', 
            'required' => true, 
            'operations' => [
                'show' => ['text' => 'name_ar']
            ]
        ];
    }
}