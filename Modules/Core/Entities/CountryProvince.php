<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryProvince extends Model{
    use SoftDeletes;
    use \Modules\BriskCore\Traits\ModelTrait;

    protected $table = 'core_country_provinces';
    
    protected $casts = ['created_at' => 'datetime:Y-m-d H:i:s a'];
    
    protected $appends = [];

    public static function boot(){
        parent::boot();

        self::creating(function($model){
            $model->full_name = ($model->country && $model->country->name && trim($model->country->name) !== '' ? $model->country->name . " - " : "") . $model->name;
        });

        self::updating(function($model){
            $model->full_name = ($model->country && $model->country->name && trim($model->country->name) !== '' ? $model->country->name . " - " : "") . $model->name;
        });
    }
    
    public function created_by_user(){
        return $this->belongsTo(\Modules\Users\Entities\User::class, 'created_by');
    }
    
    public function country(){
        return $this->belongsTo(\Modules\Core\Entities\Country::class, 'country_id');
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