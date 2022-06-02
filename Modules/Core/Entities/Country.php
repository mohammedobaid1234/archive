<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model  {
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'core_countries';
    
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    
    protected $appends = [];

    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }
    
    public function provinces(){
        return $this->hasMany(\Modules\Core\Entities\CountryProvince::class, 'country_id');
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