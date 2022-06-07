<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model{
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'pm_categories';

    public function parent(){
        return $this->belongsTo(\Modules\Products\Entities\Category::class, 'parent_id');
    }

    public function category(){
        return $this->belongsTo(\Modules\Products\Entities\Category::class, 'parent_id');
    }

    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }

    public function childs(){
        return $this->hasMany(\Modules\Products\Entities\Category::class, 'parent_id', 'id');
    }

    public function allChilds(){
        return $this->childs()->with('allChilds');
    }

    public function scopeWhereNameLike($query, $name){
        $name = str_replace("أ", "ا", $name);
        $name = str_replace("إ", "ا", $name);
        $name = str_replace("ة", "ه", $name);
        $name = str_replace("ى", "ي", $name);

        return $query->where(\DB::raw('REPLACE(REPLACE(REPLACE(REPLACE(name, "أ", "ا"), "إ", "ا"), "ة", "ه"), "ى", "ي")'), 'like', ('%' . trim($name) . '%'));
    }

    public function _name(){
        return [
            'title' => 'اسم التصنيف',
            'input' => 'input',
            'name' => 'name',
            'required' => true,
            'operations' => [
                'show' => ['text' => 'name']
            ]
        ];
    }
}
